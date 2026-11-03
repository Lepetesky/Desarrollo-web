import argparse
import json
import time
import xml.etree.ElementTree as ET
import stomp

DESTINO = "/queue/pedidos"


class PedidosListener(stomp.ConnectionListener):
    """Manejador de eventos para recibir y procesar mensajes desde ActiveMQ."""

    def __init__(self, conn):
        self.conn = conn

    def on_error(self, frame):
        print(f"[!] Error del Broker STOMP: {frame.body}")

    def on_message(self, frame):
        headers = frame.headers
        body = frame.body
        fmt = headers.get("X-Format") or headers.get("content-type", "")

        print("\n" + "=" * 55)
        print("   [NUEVO MENSAJE RECIBIDO DESDE ACTIVEMQ]")
        print("=" * 55)
        print(f" Cabeceras: {headers}")
        print(f" Cuerpo Crudo: {body}")
        print("-" * 55)

        try:
            if "json" in fmt:
                data = json.loads(body)
                print(" Formato Identificado: JSON")
                print(f"   • ID Pedido: {data.get('id')}")
                print(f"   • Cliente:   {data.get('cliente')}")
                print(f"   • Monto:     ${data.get('monto')}")
                print(f"   • Mensaje:   {data.get('mensaje')}")

            elif "xml" in fmt:
                root = ET.fromstring(body)
                print(" Formato Identificado: XML")
                print(f"   • ID Pedido: {root.findtext('id')}")
                print(f"   • Cliente:   {root.findtext('cliente')}")
                print(f"   • Monto:     ${root.findtext('monto')}")
                print(f"   • Mensaje:   {root.findtext('mensaje')}")

            elif fmt == "fijo":
                print(" Formato Identificado: Ancho Fijo")
                id_p = body[0:10].strip()
                cliente = body[10:40].strip()
                monto = body[40:52].strip()
                mensaje = body[52:102].strip()
                print(f"   • ID Pedido: {id_p}")
                print(f"   • Cliente:   {cliente}")
                print(f"   • Monto:     ${monto}")
                print(f"   • Mensaje:   {mensaje}")

            else:
                print(" Formato Identificado: Texto Plano")
                print(f"   • Contenido: {body}")

        except Exception as e:
            print(f"[!] Error al procesar/deserializar el mensaje: {e}")

        print("=" * 55)


def main():
    parser = argparse.ArgumentParser(
        description="Consumidor STOMP para escucha de pedidos en ActiveMQ."
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

    conn = stomp.Connection12([(args.host, args.port)])
    listener = PedidosListener(conn)
    conn.set_listener("pedidos_listener", listener)

    try:
        conn.connect(args.usuario, args.clave, wait=True)
        # Suscribirse a la cola compartida
        conn.subscribe(destination=DESTINO, id=1, ack="auto")
        print(
            f"[*] Consumidor activo escuchando en '{DESTINO}' ({args.host}:{args.port})..."
        )
        print("[*] Presiona Ctrl+C para detener la ejecución.\n")

        while True:
            time.sleep(1)

    except KeyboardInterrupt:
        print("\n[*] Deteniendo el consumidor...")
    except Exception as e:
        print(f"[!] Error de conexión: {e}")
    finally:
        if conn.is_connected():
            conn.disconnect()
            print("[*] Conexión cerrada.")


if __name__ == "__main__":
    main()