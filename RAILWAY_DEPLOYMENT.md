# Railway Deployment Guide

## Environment Variables

Set these environment variables in your Railway project:

### Required Variables

```
APP_NAME="Korporatio API"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/temp/korporatio_api

# Cache and Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue
QUEUE_CONNECTION=sync

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

### Optional Variables

```
# Mail (if you need email functionality)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="Korporatio API"
```

## Deployment Steps

1. **Push your code to Railway**

    - Railway will automatically detect the Dockerfile
    - The build should complete successfully

2. **Set Environment Variables**

    - Go to your Railway project dashboard
    - Navigate to Variables tab
    - Add all the required variables above

3. **Deploy**

    - Railway will automatically deploy after the build
    - Check the logs for any errors

4. **Test Your API**
    - Health check: `https://your-app-name.railway.app/api/health`
    - API base: `https://your-app-name.railway.app/api/v1`

## Troubleshooting

### If the site doesn't load:

1. Check Railway logs for errors
2. Ensure all environment variables are set
3. Verify the APP_URL matches your Railway domain
4. Check if the SQLite database file is being created

### Common Issues:

-   **500 Error**: Usually means APP_KEY is missing or invalid
-   **Database Error**: Check if `/temp/korporatio_api` file exists and is writable
-   **Permission Error**: The Dockerfile should handle this automatically

### Logs to Check:

-   Railway build logs
-   Railway deployment logs
-   Application logs (if available)
