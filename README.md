backend/
   │
   ├── app/
   │   │
   │   ├── controllers/
   │   │   ├── AlumnoController.php
   │   │   ├── AuthController.php
   │   │   ├── BecaController.php
   │   │   └── ImssController.php
   │   │
   │   ├── models/
   │   │   ├── Alumno.php
   │   │   ├── Usuario.php
   │   │   ├── Beca.php
   │   │   └── Imss.php
   │   │
   │   ├── services/
   │   │   ├── DatabaseService.php
   │   │   ├── XmlService.php
   │   │   ├── AuthService.php
   │   │   └── GoogleSheetsService.php
   │   │
   │   ├── validators/
   │   │   ├── AlumnoValidator.php
   │   │   ├── AuthValidator.php
   │   │   └── SecurityValidator.php
   │   │
   │   ├── middleware/
   │   │   ├── AuthMiddleware.php
   │   │   ├── CorsMiddleware.php
   │   │   └── ErrorMiddleware.php
   │   │
   │   ├── helpers/
   │   │   ├── ResponseHelper.php
   │   │   ├── SecurityHelper.php
   │   │   └── XmlHelper.php
   │   │
   │   └── config/
   │       ├── database.php
   │       ├── app.php
   │       └── cors.php
   │
   ├── routes/
   │   ├── alumnos.php
   │   ├── auth.php
   │   ├── becas.php
   │   ├── imss.php
   │   └── web.php
   │
   ├── public/
   │   ├── index.php
   │   └── .htaccess
   │
   ├── storage/
   │   ├── logs/
   │   ├── xml/
   │   └── uploads/
   │
   ├── database/
   │   ├── migrations/
   │   └── seeders/
   │
   ├── tests/
   │
   ├── vendor/
   │
   ├── .env
   ├── .gitignore
   ├── composer.json
   └── README.md
   | 
   ├── docs/
   ├── api-docs.md
   ├── database.md
   ├── endpoints.md
   └── security.md
    README.md