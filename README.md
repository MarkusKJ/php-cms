# Retro Tweet

Retro Tweet is a Twitter-like application with a 1970s computer terminal aesthetic. It's built using PHP and MySQL, designed to provide a nostalgic social media experience.

## Features

- User registration and authentication
- Create and view tweets (limited to 280 characters)
- User profiles with recent tweets
- Retro-style interface with green text on a black background

## Project Structure

```
retro-tweet/
├── config/
│   └── database.php (ignored by git)
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── csrf.php
├── assets/
│   └── css/
│       └── style.css
├── index.php
├── register.php
├── login.php
├── profile.php
├── create_tweet.php
├── user_tweets.php
├── logout.php
└── README.md
```

## Setup

1. Clone this repository to your local machine.
2. Set up a local server environment (like XAMPP, MAMP, or WAMP).
3. Create a MySQL database named `retro_tweet`.
4. Copy `config/database.example.php` to `config/database.php` and update with your database credentials.
5. Import the SQL schema from `database_schema.sql` (if provided, or create tables manually).
6. Navigate to the project in your web browser.

## Usage

- Register a new account or log in with existing credentials.
- Create new tweets from your profile or the home page.
- View tweets from all users on the home page.
- Visit user profiles to see their recent tweets.

## Contributing

This is a personal learning project. While suggestions are welcome, active contributions are not being accepted at this time.

## License

This project is open source and available under the [MIT License](LICENSE).
