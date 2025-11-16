#!/bin/bash

cd /home/easyrdf/fuseki && ./fuseki-server --port=3030 --update --loc /home/easyrdf/fuseki/databases/DB /ds &

until nc -z localhost 3030; do
  echo "Warten auf Fuseki..."
  sleep 2
done

echo "Fuseki läuft im Hintergrund (PID $!). Starten der interaktiven Shell..."

# Starten Sie die Bash im Vordergrund. Der Container bleibt aktiv, solange die Bash läuft.
exec /bin/bash
