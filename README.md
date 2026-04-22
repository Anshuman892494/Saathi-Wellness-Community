# Saathi Wellness Community

A full-stack social platform for health, mindfulness, and collective well-being, built with **Laravel 13 + MongoDB + Bootstrap 5**.

---

## ✨ Features

| Feature | Description |
|---|---|
| **Authentication** | Register, Login, Logout with secure password hashing |
| **Community Feed** | Browse, search, and filter all community posts |
| **Post CRUD** | Create, read, update, delete posts with categories & tags |
| **Comments** | Add and delete comments on posts |
| **Likes** | AJAX-powered like/unlike toggle with live count |
| **Bookmarks** | Save posts for later, manage your reading list |
| **User Profiles** | Public profile pages with post history and stats |
| **Wellness Hub** | Static resource pages: Health Tips, Meditation, Fitness, Nutrition |
| **Search & Filter** | Full-text search + category filter + sort by Latest/Most Liked |
| **Dashboard** | Personalised view: stats, latest posts, trending, quick actions |
| **Responsive UI** | Dark-mode design, Bootstrap 5, mobile-friendly |

---

## 🧱 Tech Stack

- **Backend**: Laravel 13 (PHP)
- **Database**: MongoDB (`mongodb/laravel-mongodb` package)
- **Frontend**: Blade Templates, Bootstrap 5, Vanilla CSS
- **Fonts**: Google Fonts (Inter + Playfair Display)
- **Icons**: Bootstrap Icons

---

## 🚀 Setup Instructions

### Prerequisites

- PHP >= 8.2
- Composer
- MongoDB server running locally (default port 27017)
- PHP `mongodb` extension installed

### 1. Clone / Navigate to Project

```bash
cd c:\Users\anshu\OneDrive\Desktop\Projects\Laravel
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy `.env.example` to `.env` (already done) and verify:

```env
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=wellness_community
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Seed Sample Data

```bash
php artisan db:seed
```

This creates 3 sample users and 5 rich posts.

**Demo credentials:**
- Email: `sarah@saathi.com`
- Password: `password`

### 6. Start the Development Server

```bash
php artisan serve
```

Visit: [http://localhost:8000](http://localhost:8000)

---

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   ├── Auth/AuthController.php      # Register, Login, Logout
│   ├── DashboardController.php      # Personalised dashboard
│   ├── PostController.php           # Full CRUD posts
│   ├── CommentController.php        # Comment store/delete
│   ├── LikeController.php           # Toggle likes (AJAX)
│   ├── BookmarkController.php       # Toggle & list bookmarks
│   ├── ProfileController.php        # View & edit profiles
│   └── ResourceController.php      # Wellness resource pages
├── Models/
│   ├── User.php                     # MongoDB User model
│   ├── Post.php                     # MongoDB Post model
│   └── Comment.php                  # MongoDB Comment model

resources/views/
├── layouts/app.blade.php            # Master layout (navbar + footer)
├── auth/                            # register.blade.php, login.blade.php
├── dashboard/index.blade.php        # User dashboard
├── posts/                           # index, create, edit, show
├── profile/                         # show, edit
├── bookmarks/index.blade.php        # Saved posts
└── resources/                       # index, health-tips, meditation, fitness, nutrition

public/css/app.css                   # Custom dark-mode CSS
routes/web.php                       # All application routes
database/seeders/DatabaseSeeder.php  # Sample data
```

---

## 🗄️ MongoDB Collections

| Collection | Key Fields |
|---|---|
| `users` | `_id`, `name`, `email`, `password`, `bio`, `bookmarks[]` |
| `posts` | `_id`, `user_id`, `title`, `content`, `category`, `tags[]`, `likes[]`, `views` |
| `comments` | `_id`, `post_id`, `user_id`, `comment` |

---

## 🎨 Design System

- **Primary color**: `#2daa6f` (emerald green)
- **Accent**: `#17a3b8` (teal)
- **Background**: `#0f1117` (near black)
- **Typography**: Inter (body) + Playfair Display (headings)
- **Style**: Dark glassmorphic cards, gradient accents, smooth micro-animations

---

## 📋 Post Categories

- `general` — General wellness discussions
- `fitness` — Exercise, workouts, movement
- `mental-health` — Mental health, therapy, mindfulness
- `nutrition` — Diet, food, eating habits
- `meditation` — Meditation, yoga, breathwork

---

## 🔐 Authorization Rules

- Anyone can **read** posts and view profiles
- **Auth required** to create posts, comment, like, bookmark
- Only the **post/comment author** can edit or delete their own content

---

## 💡 Notes

- The PHP MongoDB extension (`ext-mongodb`) must be installed and enabled in your `php.ini`
- If MongoDB requires auth, set `DB_USERNAME` and `DB_PASSWORD` in `.env`
- Sessions are stored as files (`SESSION_DRIVER=file`) for MongoDB compatibility
