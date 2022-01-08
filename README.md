# storage-server
Web-приложение, позволяющее выгружать файлы на сервер, скачивать его, а также удалять. 

### POST /api/file
Загрузка файла.
![File upload](./screenshots/post-file.png)

### GET /api/file/{filename}
Скачивание файла. 
![File save](./screenshots/get-file.png)

### DELETE /api/file/{filename}
Удаление файла.
![File delete](./screenshots/delete-file.png)

### GET /api/file-info/
Информация о всех файлах: название и размер в байтах. 
![File save](./screenshots/get-file-info.png)
