# API Documentation

Dokumentasi ini menjelaskan cara menggunakan API untuk mengelola News (Berita) dan Gallery (Galeri) pada aplikasi Galeri Sekolah.

## Base URL
```
http://localhost:8000/api
```

## Authentication
Beberapa endpoint memerlukan autentikasi menggunakan token Sanctum. Tambahkan header berikut untuk request yang membutuhkan autentikasi:
```
Authorization: Bearer {token}
Accept: application/json
```

## News API

### 1. Get All News
**GET** `/news`

**Parameters:**
- `search` (optional): Pencarian berdasarkan judul atau isi berita
- `category_id` (optional): Filter berdasarkan kategori
- `is_published` (optional): Filter berdasarkan status publish (true/false)
- `per_page` (optional): Jumlah data per halaman (default: 10)

**Example Request:**
```http
GET /api/news?search=belajar&category_id=1&is_published=true&per_page=5
```

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "title": "Judul Berita",
                "slug": "judul-berita-1234567890",
                "content": "Isi berita...",
                "image": "news/image.jpg",
                "author": "Admin",
                "is_published": true,
                "published_at": "2023-01-01T00:00:00.000000Z",
                "created_at": "2023-01-01T00:00:00.000000Z",
                "updated_at": "2023-01-01T00:00:00.000000Z",
                "admin_id": 1,
                "kategori_id": 1,
                "kategori": {
                    "id": 1,
                    "nama": "Umum",
                    "created_at": null,
                    "updated_at": null
                }
            }
        ],
        "first_page_url": "http://localhost:8000/api/news?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://localhost:8000/api/news?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/news?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://localhost:8000/api/news",
        "per_page": 10,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### 2. Get Single News
**GET** `/news/{slug}`

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Judul Berita",
        "slug": "judul-berita-1234567890",
        "content": "Isi berita...",
        "image": "news/image.jpg",
        "author": "Admin",
        "is_published": true,
        "published_at": "2023-01-01T00:00:00.000000Z",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z",
        "admin_id": 1,
        "kategori_id": 1,
        "kategori": {
            "id": 1,
            "nama": "Umum",
            "created_at": null,
            "updated_at": null
        }
    }
}
```

### 3. Create News (Auth Required)
**POST** `/news`

**Headers:**
```
Content-Type: multipart/form-data
Authorization: Bearer {token}
```

**Body (form-data):**
- `title` (required): Judul berita
- `content` (required): Isi berita
- `image` (optional): Gambar berita (max: 2MB)
- `author` (required): Penulis berita
- `is_published` (optional): Status publish (default: false)
- `kategori_id` (required): ID kategori

**Response (Success - 201):**
```json
{
    "success": true,
    "message": "News created successfully",
    "data": {
        "title": "Judul Baru",
        "content": "Isi berita...",
        "author": "Admin",
        "kategori_id": 1,
        "is_published": true,
        "slug": "judul-baru-1234567890",
        "published_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "id": 2
    }
}
```

### 4. Update News (Auth Required)
**POST** `/news/{id}`

**Headers:**
```
Content-Type: multipart/form-data
Authorization: Bearer {token}
```

**Body (form-data):**
- `_method`: PUT
- `title` (optional): Judul berita
- `content` (optional): Isi berita
- `image` (optional): Gambar berita (max: 2MB)
- `author` (optional): Penulis berita
- `is_published` (optional): Status publish
- `kategori_id` (optional): ID kategori

**Response (Success):**
```json
{
    "success": true,
    "message": "News updated successfully",
    "data": {
        "id": 1,
        "title": "Judul Diperbarui",
        "content": "Isi berita yang diperbarui...",
        "image": "news/updated-image.jpg",
        "author": "Admin",
        "is_published": true,
        "published_at": "2023-01-01T00:00:00.000000Z",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z",
        "admin_id": 1,
        "kategori_id": 1
    }
}
```

### 5. Delete News (Auth Required)
**DELETE** `/news/{id}`

**Response (Success):**
```json
{
    "success": true,
    "message": "News deleted successfully"
}
```

### 6. Get News Categories
**GET** `/news/categories`

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama": "Umum",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 2,
            "nama": "Kegiatan",
            "created_at": null,
            "updated_at": null
        }
    ]
}
```

## Gallery API

### 1. Get All Galleries
**GET** `/galleries`

**Parameters:**
- `search` (optional): Pencarian berdasarkan judul atau deskripsi
- `category_id` (optional): Filter berdasarkan kategori
- `is_published` (optional): Filter berdasarkan status publish (true/false)
- `per_page` (optional): Jumlah data per halaman (default: 12)

### 2. Get Single Gallery
**GET** `/galleries/{id}`

### 3. Create Gallery (Auth Required)
**POST** `/galleries`

**Headers:**
```
Content-Type: multipart/form-data
Authorization: Bearer {token}
```

**Body (form-data):**
- `title` (required): Judul galeri
- `description` (optional): Deskripsi galeri
- `image` (required): Gambar galeri (max: 2MB)
- `is_published` (optional): Status publish (default: false)
- `kategori_id` (required): ID kategori

### 4. Update Gallery (Auth Required)
**POST** `/galleries/{id}`

**Headers:**
```
Content-Type: multipart/form-data
Authorization: Bearer {token}
```

**Body (form-data):**
- `_method`: PUT
- `title` (optional): Judul galeri
- `description` (optional): Deskripsi galeri
- `image` (optional): Gambar galeri (max: 2MB)
- `is_published` (optional): Status publish
- `kategori_id` (optional): ID kategori

### 5. Delete Gallery (Auth Required)
**DELETE** `/galleries/{id}`

### 6. Get Gallery Categories
**GET** `/galleries/categories`

## Authentication

### 1. Login
**POST** `/admin/login`

**Body (JSON):**
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response (Success):**
```json
{
    "token": "1|abcdefghijklmnopqrstuvwxyz",
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@example.com",
        "email_verified_at": null,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
    }
}
```

### 2. Logout (Auth Required)
**POST** `/admin/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (Success):**
```json
{
    "message": "Logged out successfully"
}
```

### 3. Get Authenticated User (Auth Required)
**GET** `/admin/user`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (Success):**
```json
{
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com",
    "email_verified_at": null,
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
}
```

## Error Responses

### 401 Unauthorized
```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
    "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
    "success": false,
    "message": "News not found"
}
```

### 422 Unprocessable Entity (Validation Error)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": [
            "The title field is required."
        ],
        "kategori_id": [
            "The selected kategori id is invalid."
        ]
    }
}
```
