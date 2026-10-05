$ErrorActionPreference = 'Stop'
# Se conservan los secretos de semana 8 y se agrega el del Auth Service.
foreach ($nombre in @('VAULT_TOKEN', 'CLIENT_TOKEN', 'BACKEND_SHARED_SECRET', 'AUTH_INTROSPECTION_SECRET')) {
    if (-not [Environment]::GetEnvironmentVariable($nombre)) {
        throw "Falta configurar la variable $nombre"
    }
}
$direccion = $env:VAULT_ADDR
if (-not $direccion) { $direccion = 'http://127.0.0.1:8200' }
$cuerpo = @{
    data = @{
        client_token = $env:CLIENT_TOKEN
        backend_shared_secret = $env:BACKEND_SHARED_SECRET
        auth_introspection_secret = $env:AUTH_INTROSPECTION_SECRET
    }
} | ConvertTo-Json -Depth 3
Invoke-RestMethod -Method Post -Uri "$($direccion.TrimEnd('/'))/v1/secret/data/gateway" `
    -Headers @{ 'X-Vault-Token' = $env:VAULT_TOKEN } -ContentType 'application/json' -Body $cuerpo | Out-Null
Write-Host 'Secretos configurados en secret/gateway.'
