$ErrorActionPreference = 'Stop'
if (-not $env:AUTH_INTROSPECTION_SECRET) { throw 'Configura AUTH_INTROSPECTION_SECRET en esta terminal.' }
$base = 'http://127.0.0.1:8100'
$headers = @{ 'X-Auth-Secret' = $env:AUTH_INTROSPECTION_SECRET }
$sesion = Invoke-RestMethod -Method Post -Uri "$base/login" -ContentType 'application/json' `
    -Body (@{ username = 'ana'; password = '1234' } | ConvertTo-Json)
if ($sesion.expires_in -ne 900 -or -not $sesion.access_token) { throw 'Login incorrecto.' }
$cuerpo = @{ token = $sesion.access_token } | ConvertTo-Json
$estado = Invoke-RestMethod -Method Post -Uri "$base/introspect" -Headers $headers `
    -ContentType 'application/json' -Body $cuerpo
if (-not $estado.active -or $estado.username -ne 'ana') { throw 'La sesión no está activa.' }
Write-Host 'OK: login e introspection.'
Invoke-RestMethod -Method Post -Uri "$base/logout" -Headers $headers `
    -ContentType 'application/json' -Body $cuerpo | Out-Null
$estado = Invoke-RestMethod -Method Post -Uri "$base/introspect" -Headers $headers `
    -ContentType 'application/json' -Body $cuerpo
if ($estado.active) { throw 'Logout no revocó el token.' }
Write-Host 'OK: logout deja active=false.'
try {
    Invoke-RestMethod -Method Post -Uri "$base/login" -ContentType 'application/json' `
        -Body (@{ username = 'ana'; password = 'incorrecta' } | ConvertTo-Json) | Out-Null
    throw 'Se aceptó una contraseña incorrecta.'
} catch {
    if (-not $_.Exception.Response -or [int]$_.Exception.Response.StatusCode -ne 401) { throw }
    Write-Host 'OK: contraseña incorrecta devuelve 401.'
}
