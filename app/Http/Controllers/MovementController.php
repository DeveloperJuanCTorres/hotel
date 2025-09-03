<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Invoice;
use App\Models\Movement;
use App\Models\Room;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovementController extends Controller
{
    public function generar(Request $request)
    {
        try {
            $business = Business::first();
            $tipo = $request->tipo;
            $tipo_comprobante = 0;
            $serie = '';
            $numero = 0;
            $tipo_doc = 0;
            $igv = 10.00;
            $subtotal = $request->total / 1.10;
            $total_igv = $request->total - $subtotal;
            $products = [];
            
            switch ($tipo) {
                case 'ticket':
                    $invoice = Invoice::where('name', 'Ticket')->first();
                    if (!$invoice) {
                        return response()->json(['status' => false, 'msg' => 'No existe configuración para '.$tipo]);
                    }
                    $serie = $invoice->serie;
                    $numero = $invoice->start + $invoice->contar;
                    $invoice->contar = $invoice->contar + 1;
                    $invoice->save();
                    break;
                case 'boleta':
                    $invoice = Invoice::where('name', 'Boleta Electrónica')->first();
                    if (!$invoice) {
                        return response()->json(['status' => false, 'msg' => 'No existe configuración para '.$tipo]);
                    }
                    $tipo_comprobante = 2;
                    $serie = $invoice->serie;
                    $numero = $invoice->start + $invoice->contar;
                    $invoice->contar = $invoice->contar + 1;
                    $invoice->save();
                    break;
                case 'factura':
                    $invoice = Invoice::where('name', 'Factura Electrónica')->first();
                    if (!$invoice) {
                        return response()->json(['status' => false, 'msg' => 'No existe configuración para '.$tipo]);
                    }
                    $tipo_comprobante = 1;
                    $serie = $invoice->serie;
                    $numero = $invoice->start + $invoice->contar;
                    $invoice->contar = $invoice->contar + 1;
                    $invoice->save();
                    break;
                
                default:
                    return response()->json(['status' => false, 'msg' => 'Tipo no válido'], 400);
            }

            switch ($request->tipo_doc) {
                case 'DNI':
                    $tipo_doc = 1;
                    break;
                case 'RUC':
                    $tipo_doc = 6;
                    break;
                case 'PASPORTE':
                    $tipo_doc = 7;
                    break;
                
                default:
                    return response()->json(['error' => 'Tipo no válido'], 400);
            }
            $valor_unitario_sin_igv = $request->valor_unitario / 1.10;

            $valor_unitario_igv = $request->valor_unitario - $valor_unitario_sin_igv;

            $product = array(
                "unidad_de_medida"=> "ZZ",
                "codigo"=> "Z001",
                "codigo_producto_sunat"=> "10000000",
                "descripcion"=> "Servicio de alojamiento de la habitacion " . $request->habitacion,
                "cantidad"=> $request->total_dias,
                "valor_unitario"=> $valor_unitario_sin_igv,
                "precio_unitario"=> $request->valor_unitario,
                "descuento"=> "",
                "subtotal"=> ($valor_unitario_sin_igv*$request->total_dias),
                "tipo_de_igv"=> 1,
                "igv"=> $valor_unitario_igv,
                "total"=> $request->total,
                "anticipo_regularizacion"=> false,
                "anticipo_documento_serie"=> "",
                "anticipo_documento_numero"=> ""
            );
            array_push($products, $product);

            $date_now = Carbon::now()->format('d-m-Y');
            $store = array(
                "operacion"=> "generar_comprobante",
                "tipo_de_comprobante"=> $tipo_comprobante,
                "serie"=> $serie,
                "numero"=> $numero,
                "sunat_transaction"=> 1,

                "cliente_tipo_de_documento"=> $tipo_doc,

                "cliente_numero_de_documento"=> $request->numero_doc,
                "cliente_denominacion"=> $request->name,
                "cliente_direccion"=> $request->address,
                "cliente_email"=> "",
                "cliente_email_1"=> "",
                "cliente_email_2"=> "",
                "fecha_de_emision"=> $date_now,
                "fecha_de_vencimiento"=> "",
                "moneda"=> 1,
                "tipo_de_cambio"=> "",
                "porcentaje_de_igv"=> $igv,
                "descuento_global"=> "",
                "total_descuento"=> "",
                "total_anticipo"=> "",
                "total_gravada"=> $subtotal,
                "total_inafecta"=> "",
                "total_exonerada"=> "",
                "total_igv"=> $total_igv,
                "total_gratuita"=> "",
                "total_otros_cargos"=> "",
                "total"=> $request->total,
                "percepcion_tipo"=> "",
                "percepcion_base_imponible"=> "",
                "total_percepcion"=> "",
                "total_incluido_percepcion"=> "",
                "retencion_tipo"=> "",
                "retencion_base_imponible"=> "",
                "total_retencion"=> "",
                "total_impuestos_bolsas"=> "",
                "detraccion"=> false,
                "observaciones"=> "",
                "documento_que_se_modifica_tipo"=> "",
                "documento_que_se_modifica_serie"=> "",
                "documento_que_se_modifica_numero"=> "",
                "tipo_de_nota_de_credito"=> "",
                "tipo_de_nota_de_debito"=> "",
                "enviar_automaticamente_a_la_sunat"=> true,
                "enviar_automaticamente_al_cliente"=> false,
                "condiciones_de_pago"=> "",
                "medio_de_pago"=> "",
                "placa_vehiculo"=> "",
                "orden_compra_servicio"=> "",  
                "formato_de_pdf"=> "TICKET",
                "generado_por_contingencia"=> "",
                "bienes_region_selva"=> "",
                "servicios_region_selva"=> "",
                "items" => $products                
            );  

            $respuesta = Http::withHeaders(
                ['Authorization' => $business->token_fact])
            ->post($business->ruta_fact, $store);

            if ($respuesta->status()==200) {
                $resp = json_decode($respuesta->body());
                $movimiento = new Movement();
                $movimiento->transaction_id = $request->transaction_id;
                $movimiento->type = 'hospedaje';
                $movimiento->referencia = $serie . '-' . $numero;
                $movimiento->total = $request->total;
                $movimiento->estado_sunat = $resp->aceptada_por_sunat;
                $movimiento->observacion_sunat = $resp->sunat_description;
                $movimiento->pdf_sunat = $resp->enlace_del_pdf;
                $movimiento->xml_sunat = $resp->enlace_del_xml;
                $movimiento->cdr_sunat = $resp->enlace_del_cdr;
               
                $movimiento->save();

                $transaction = Transaction::findOrFail($request->transaction_id);
                $transaction->status = 1;
                $transaction->save();

                $room = Room::where('numero',$request->habitacion)->first();
                $room->status = 'LIMPIEZA';
                $room->save();

                return response()->json([
                    'status' => true, 
                    'msg' => 'registro con éxito',
                    'ruta_pdf' => $resp->enlace_del_pdf]);
            }
            else
            {
                $resp = json_decode($respuesta);
                $test = json_encode($store);
                return response()->json(['status' => false, 'msg' => $respuesta->body() . $test]);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
        }   
    }
}