# Usuarios simulados de la guía. La contraseña de laboratorio es 1234.
# Guardamos el hash Argon2, no la contraseña en el diccionario.
USERS = {
    "ana": {
        "user_id": "USR-001",
        "password_hash": '$argon2id$v=19$m=65536,t=3,p=4$bxQOcZTiguHp6DgbSsFQrg$EwmY3BQDPYUdQ5DVkijAEmYRSXHlPXdQ3U1S0Bt9bIQ',
        "roles": ["user"],
    },
    "ernesto": {
        "user_id": "USR-003",
        "password_hash": '$argon2id$v=19$m=65536,t=3,p=4$csj/awPgeO9/pfdcmx4oFw$g1kTkuWkfgYHdE6c4tRviBa9pYtP+lBBMRoEXVo2L+c',
        "roles": ["user", "admin"],
    },
}
