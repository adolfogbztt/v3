<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

use Greenter\Model\Client\Client;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Document;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/vendor/autoload.php';

class GoGuiaRemision
{
    public $VENTA;
    public $VENTADETALLE;
    public $MIEMPRESA;
    public $CLIENTE;
    public $GETUBIGEOS;
    public $conn;

    // constructor with $db as database connection
    public function __construct($VENTA, $VENTADETALLE, $MIEMPRESA, $CLIENTE, $UBIGEOCLIENTE, $UBIGEOEMPRESA, $getdb)
    {
        $this->VENTA = $VENTA;
        $this->VENTADETALLE = $VENTADETALLE;
        $this->MIEMPRESA = $MIEMPRESA;
        $this->CLIENTE = $CLIENTE;
        $this->getdb = $getdb;
        /* $this->$UBIGEOCLIENTE = $UBIGEOCLIENTE; */
        $this->UBIGEOEMPRESA = $UBIGEOEMPRESA;
    }
    public function generateFactura()
    {

        $see = require __DIR__ . '/config.php';
        $respuesta = array();
        // Cliente


require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$rel = new Document();
$rel->setTipoDoc('02') // Tipo: Numero de Orden de Entrega
->setNroDoc('213123');

$transp = new Transportist();
$transp->setTipoDoc('6')
    ->setNumDoc('20000000002')
    ->setRznSocial('TRANSPORTES S.A.C')
    ->setPlaca('ABI-453')
    ->setChoferTipoDoc('1')
    ->setChoferDoc('40003344');

$envio = new Shipment();
$envio
    ->setCodTraslado('01') // Cat.20
    ->setDesTraslado('VENTA')
    ->setModTraslado('01') // Cat.18
    ->setFecTraslado(new DateTime())
    ->setCodPuerto('123')
    ->setIndTransbordo(false)
    ->setPesoTotal(12.5)
    ->setUndPesoTotal('KGM')
//    ->setNumBultos(2) // Solo válido para importaciones
    ->setNumContenedor('XD-2232')
    ->setLlegada(new Direction('150101', 'AV LIMA'))
    ->setPartida(new Direction('150203', 'AV ITALIA'))
    ->setTransportista($transp);

$despatch = new Despatch();
$despatch->setTipoDoc('09')
    ->setSerie('T001')
    ->setCorrelativo('123')
    ->setFechaEmision(new DateTime())
    ->setCompany($util->shared->getCompany())
    ->setDestinatario((new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000002')
        ->setRznSocial('EMPRESA (<!-- --> />) 1'))
    ->setTercero((new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000003')
        ->setRznSocial('EMPRESA SA'))
    ->setObservacion('NOTA GUIA')
    ->setRelDoc($rel)
    ->setEnvio($envio);

$detail = new DespatchDetail();
$detail->setCantidad(2)
    ->setUnidad('ZZ')
    ->setDescripcion('PROD 1')
    ->setCodigo('PROD1')
    ->setCodProdSunat('P001');

$despatch->setDetails([$detail]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::GUIA_BETA);

$res = $see->send($despatch);
$util->writeXml($despatch, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res BillResult*/
    $cdr = $res->getCdrResponse();
    $util->writeCdr($despatch, $res->getCdrZip());

    $util->showResponse($despatch, $cdr);
} else {
    echo $util->getErrorResponse($res->getError());
}
////////////////////////////////////////////////////////////////
        $client = new Client();
        $client->setTipoDoc($this->CLIENTE['ane_tipdoc'])
            ->setNumDoc($this->CLIENTE['ane_numdoc'])
            ->setRznSocial($this->CLIENTE['ane_razsoc']);

        // Emisor
        $address = new Address();
        $address->setUbigueo($this->UBIGEOEMPRESA['ubi_id'])
            ->setDepartamento($this->UBIGEOEMPRESA['departamento'])
            ->setProvincia($this->UBIGEOEMPRESA['provincia'])
            ->setDistrito($this->UBIGEOEMPRESA['distrito'])
            ->setUrbanizacion('-')
            ->setDireccion($this->MIEMPRESA['emp_dir'])
            ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 de lo contrario.

        $company = new Company();
        $company->setRuc($this->MIEMPRESA['emp_ruc'])
            ->setRazonSocial($this->MIEMPRESA['emp_nomcom'])
            ->setNombreComercial($this->MIEMPRESA['emp_nom'])
            ->setAddress($address);

        // Venta
        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Venta - Catalog. 51
            ->setTipoDoc('01') // Factura - Catalog. 01 
            ->setSerie($this->VENTA['ven_ser'])
            ->setCorrelativo($this->VENTA['ven_num'])
            ->setFechaEmision(new DateTime($this->VENTA['ven_fecemi']))
            ->setTipoMoneda($this->VENTA['mnd_id']) // Sol - Catalog. 02
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas(round($this->VENTA['ven_afe'], 2))
            ->setMtoOperInafectas(round($this->VENTA['ven_ina'], 2))
            ->setMtoIGV(18.00)
            ->setTotalImpuestos(round($this->VENTA['ven_igv'], 2))
            ->setValorVenta(round($this->VENTA['ven_afe'] + $this->VENTA['ven_ina'], 2))
            ->setSubTotal(round($this->VENTA['ven_totdoc'], 2))
            ->setMtoImpVenta(round($this->VENTA['ven_totdoc'], 2));

        $letItemsArray = array();
        foreach ($this->VENTADETALLE as $k => $v) {
            /*    var_dump($k,$v); */
            $database = new Database();
            $db = $database->getConnection($this->getdb);
            $fe = new FE($db, null, null);

            $infoProducto = $fe->getDetalleProducto($this->VENTADETALLE[$k]['vt_pro_id']);
            $PRODUCTOS = $infoProducto->fetch(PDO::FETCH_ASSOC);

            $infoPresentacion = $fe->getPresentacionPorducto($PRODUCTOS['pst_id']);
            $PRESENTACION = $infoPresentacion->fetch(PDO::FETCH_ASSOC);

            //reconocedor de tipo de items (super Importante )
            switch ($this->VENTADETALLE[$k]['vd_ina']) {
                    //zona de gravados normales
                case '0':
                    //producto afecto/gravado 
                    if ($this->VENTADETALLE[$k]['vd_gra'] == 0) {
                        $item = (new SaleDetail())
                            ->setCodProducto($PRODUCTOS['pro_bar'])
                            ->setUnidad($PRESENTACION['pst_snt']) // Unidad - Catalog. 03
                            ->setDescripcion($PRODUCTOS['pro_nom'])
                            ->setCantidad(round($this->VENTADETALLE[$k]['vd_can'], 2)) // cantidad
                            ->setMtoValorUnitario($valorUnitario = round($this->VENTADETALLE[$k]['vd_pre'] - $this->VENTADETALLE[$k]['vd_igv_unico'], 2)) // valor unitario (sin igv)
                            ->setMtoValorVenta(round($valorUnitario * $this->VENTADETALLE[$k]['vd_can']))   // valor unitario * cantidad sin igv
                            ->setMtoBaseIgv(round($this->VENTADETALLE[$k]['vd_pre'] * $this->VENTADETALLE[$k]['vd_can']))         // valor unitario * cantidad sin igv  
                            ->setPorcentajeIgv(18.00) // 18%
                            ->setIgv(round($this->VENTADETALLE[$k]['vd_total_igv']), 2) // igv del valor unitario  * cantidad 
                            ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                            ->setTotalImpuestos(round($this->VENTADETALLE[$k]['vd_total_igv']), 2)  // igv del valor unitario * cantidad
                            ->setMtoPrecioUnitario(round($this->VENTADETALLE[$k]['vd_pre']), 2); // precio total del producto unitario con igv 
                    } else {
                        //producto afecto/gravado gratuito 
                        $item = (new SaleDetail())
                            ->setCodProducto('P004')
                            ->setUnidad('NIU')
                            ->setDescripcion('PROD 4')
                            ->setCantidad(1)
                            ->setMtoValorUnitario(0)
                            ->setMtoValorGratuito(100)
                            ->setMtoValorVenta(100)
                            ->setMtoBaseIgv(100)
                            ->setPorcentajeIgv(18)
                            ->setIgv(18)
                            ->setTipAfeIgv('13') // Catalog 07: Gravado - Retiro,
                            ->setTotalImpuestos(18)
                            ->setMtoPrecioUnitario(0);
                    }
                    break;

                case '1':
                    //producto inafecto/no gravado 
                    if ($this->VENTADETALLE[$k]['vd_gra'] == 0) {
                        $item = (new SaleDetail())
                            ->setCodProducto($PRODUCTOS['pro_bar'])
                            ->setUnidad($PRESENTACION['pst_snt']) // Unidad - Catalog. 03
                            ->setDescripcion($PRODUCTOS['pro_nom'])
                            ->setCantidad(round($this->VENTADETALLE[$k]['vd_can'], 2)) // cantidad
                            ->setMtoValorUnitario($valorUnitario = round($this->VENTADETALLE[$k]['vd_pre'] - $this->VENTADETALLE[$k]['vd_igv_unico'], 2)) // valor unitario (sin igv)
                            ->setMtoValorVenta(round($valorUnitario * $this->VENTADETALLE[$k]['vd_can']))   // valor unitario * cantidad sin igv
                            ->setMtoBaseIgv(round($this->VENTADETALLE[$k]['vd_pre'] * $this->VENTADETALLE[$k]['vd_can']))         // valor unitario * cantidad sin igv  
                            ->setPorcentajeIgv(0) // 18%
                            ->setIgv(0) // igv del valor unitario  * cantidad 
                            ->setTipAfeIgv('30') // Gravado Op. Onerosa - Catalog. 07
                            ->setTotalImpuestos(0)  // igv del valor unitario * cantidad
                            ->setMtoPrecioUnitario(round($this->VENTADETALLE[$k]['vd_pre']), 2); // precio total del producto unitario con igv 
                    } else {
                        //producto inafecto/no gravado 
                        //
                        $item = (new SaleDetail())
                            ->setCodProducto('P005')
                            ->setUnidad('NIU')
                            ->setDescripcion('PROD 5')
                            ->setCantidad(2)
                            ->setMtoValorUnitario(0)
                            ->setMtoValorGratuito(100)
                            ->setMtoValorVenta(200)
                            ->setMtoBaseIgv(200)
                            ->setPorcentajeIgv(0)
                            ->setIgv(0)
                            ->setTipAfeIgv('32') // Catalog 07: Inafecto - Retiro,
                            ->setTotalImpuestos(0)
                            ->setMtoPrecioUnitario(0);
                    }
                    break;

                default:
                    echo "ya valiste";
                    break;
            }
            array_push($letItemsArray, $item);
        }


        $num = new Num2letras();
        $legend = (new Legend())
            ->setCode('1000') // Monto en letras - Catalog. 52
            ->setValue($num->numero(round($this->VENTA['ven_totdoc'], 2)));

        $invoice->setDetails($letItemsArray)
            ->setLegends([$legend]);
        $result = $see->send($invoice);

        // Guardar XML firmado digitalmente.
        file_put_contents(
            '../folders/' . $this->getdb.'/'.$invoice->getName() . '.xml',
            $see->getFactory()->getLastXml()
        );
        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
            $v1 = $result->getError()->getCode();
            $v2 = $result->getError()->getMessage();
            $v1 = preg_replace('([^A-Za-z0-9])', ' ', $v1);
            $v2 = preg_replace('([^A-Za-z0-9])', ' ', $v2);

            array_push($respuesta, array("status" => "RECHAZADA", "detail" => array(
                "Codigo Error" => "$v1",
                "Mensaje Error" => "$v2"
            )));
            return $respuesta;
        }

        // Guardamos el CDR
        file_put_contents('../folders/' . $this->getdb . '/R-' . $invoice->getName() . '.zip', $result->getCdrZip());

        $cdr = $result->getCdrResponse();

        $code = (int)$cdr->getCode();


        $return = array();
        if ($code === 0) {
            http_response_code(200);
            array_push($respuesta, array("status" => "ACEPTADA"));
        } else if ($code >= 4000) {
            http_response_code(502);
            array_push($respuesta, array("status" => "ACEPTADA CON OBSERVACIONES", "detail" => array(PHP_EOL), "attr" => array($cdr->getNotes())));
        } else if ($code >= 2000 && $code <= 3999) {
            http_response_code(502);
            array_push($respuesta, array("status" => "RECHAZADA", "detail" => array(PHP_EOL)));
        } else {
            /* Esto no debería darse, pero si ocurre, es un CDR inválido que debería tratarse como un error-excepción. */
            /*code: 0100 a 1999 */
            http_response_code(502);
            array_push($respuesta, array("status" => "EXCEPCION", "detail" => "esto no deberia ocurrir comuniquese con soporte tecnico inmediatamente"));
        }
        $arryCDR = array("cdr" => array($cdr->getDescription()));
        array_push($return, $respuesta, $arryCDR);
        return $return;
    }
}
 //fin Gofactura
