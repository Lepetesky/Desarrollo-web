# Este script toma los valores desde el entorno local y los guarda en Vault.
# Los secretos nunca se escriben directamente dentro del repositorio.
$requiredVariables = @(
    "VAULT_TOKEN",
    "TOKYO_CLIENT_TOKEN",
    "TOKYO_GATEWAY_SECRET"
)

foreach ($variable in $requiredVariables) {
    if (-not (Test-Path "Env:$variable") -or -not (Get-Item "Env:$variable").Value) {
        Write-Error "Falta configurar la variable $variable"
        exit 1
    }
}

$env:VAULT_ADDR = if ($env:VAULT_ADDR) { $env:VAULT_ADDR } else { "http://127.0.0.1:8200" }

docker compose exec `
    -e VAULT_TOKEN=$env:VAULT_TOKEN `
    vault vault kv put secret/tokyo-noodles/security `
    client_token=$env:TOKYO_CLIENT_TOKEN `
    gateway_secret=$env:TOKYO_GATEWAY_SECRET

if ($LASTEXITCODE -ne 0) {
    Write-Error "No fue posible guardar los secretos en Vault"
    exit $LASTEXITCODE
}

Write-Host "Secretos de Tokyo Noodles guardados correctamente en Vault."
