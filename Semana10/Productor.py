import argparse
import json
import xml.etree.ElementTree as ET
from decimal import Decimal, InvalidOperation
import stomp

DESTINO = "/queue/pedidos"


def construir_mensaje(formato, id_pedido, cliente, monto, mensaje):
    """Construye el cuerpo del mensaje y las cabeceras según el formato seleccionado."""
    try:
        monto_dec = Decimal(str(monto))
    except InvalidOperation:
        raise ValueError(
            f"El monto '{monto}' no es un valor numérico válido."
        )

    if formato == "json":
        data = {
            "id": id_pedido,
            "cliente": cliente,
            "monto": str(monto_dec),
            "mensaje": mensaje,
        }
        body = json.dumps(data, ensure_ascii=False)
        content_type = "application/json"

    elif formato == "xml":
        root = ET.Element("pedido")
        ET.SubElement(root, "id").text = str(id_pedido)
        ET.SubElement(root, "cliente").text = str(cliente)
        ET.SubElement(root, "monto").text = str(monto_dec)
        ET.SubElement(root, "mensaje").text = str(mensaje)
        body = ET.tostring(root, encoding="utf-8").decode("utf-8")
        content_type = "application/xml"

    elif formato == "fijo":
        # Formato de ancho fijo: ID(10) + Cliente(30) + Monto(12) + Mensaje(50)
        body = f"{str(id_pedido):<10}{str(cliente):<30}{str(monto_dec):<12}{str(mensaje):<50}"
        content_type = "text/plain"

    else:  # formato == 'texto'
        body = f"ID: {id_pedido} | Cliente: {cliente} | Monto: {monto_dec} | Mensaje: {mensaje}"
        content_type = "text/plain"

    headers = {
        "content-type": content_type,
        "X-Format": formato,
        "persistent": "true",
    }

    return body, headers


def main():
    parser = argparse.ArgumentParser(
        description="Productor STOMP para el envío de pedidos a ActiveMQ."
    )
    parser.add_argument(
        "--formato",
        choices=["texto", "fijo", "xml", "json"],
        default="json",
        help="Formato de serialización del mensaje",
    )
    parser.add_argument("--id", required=True, help="ID único del pedido")
    parser.add_argument(
        "--cliente", required=True, help="Nombre del cliente"
    )
    parser.add_argument(
        "--monto", required=True, help="Monto total del pedido"
    )
    parser.add_argument(
        "--mensaje", default="", help="Observaciones o detalle del pedido"
    )
    parser.add_argument(
        "--host", default="127.0.0.1", help="Dirección IP o host del Broker"
    )
    parser.add_argument(
        "--port", type=int, default=61613, help="Puerto STOMP de ActiveMQ"
    )
    parser.add_argument(
        "--usuario", default="admin", help="Usuario de autenticación"
    )
    parser.add_argument(
        "--clave", default="admin", help="Clave de autenticación"
    )

    args = parser.parse_args()

    # Creación y configuración del socket STOMP
    conn = stomp.Connection12([(args.host, args.port)])

    try:
        body, headers = construir_mensaje(
            args.formato, args.id, args.cliente, args.monto, args.mensaje
        )

        conn.connect(args.usuario, args.clave, wait=True)
        conn.send(destination=DESTINO, body=body, headers=headers)
        print(
            f"[+] Pedido #{args.id} enviado exitosamente a '{DESTINO}' en formato '{args.formato}'."
        )

    except Exception as e:
        print(f"[-] Error al enviar el mensaje al Broker: {e}")
    finally:
        if conn.is_connected():
            conn.disconnect()


if __name__ == "__main__":
    main()