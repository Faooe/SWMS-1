"functions": {
  "api/index.php": { "runtime": "vercel-php@0.9.0" },
  "api/debug-env.php": { "runtime": "vercel-php@0.9.0" },
  "api/debug-connector.php": { "runtime": "vercel-php@0.9.0" }
},
"routes": [
  ...,
  {
    "src": "/api/debug-connector.php",
    "dest": "/api/debug-connector.php"
  },
  {
    "src": "/(.*)",
    "dest": "/api/index.php"
  }
]