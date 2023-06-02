# CI4 API dengan Otentikasi Token 

Project Back-end API CI4 dengan Codeigniter Shield untuk otentikasi token sebelum melakukan request


## Installation

Cara menginstall projectnya yaitu:
- Import file database yang ada di folder database ke sql server 
- Konfigurasi nama db, user db, pw db pada file .env dalam folder
- instal dependensi dengan composer
- jalankan aplikasi dengan
```bash
  php spark serve
```
- import postman collection untuk melakukan pengetesan
- pertama-tama lakukan login untuk mendapatkan token, lalu copy token ke authorization type:Bearer Token