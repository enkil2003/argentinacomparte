<VirtualHost *:80>
    DocumentRoot "$installationPath/trunk/public"
    ServerName $domain
    
    # This should be omitted in the production environment
    SetEnv APPLICATION_ENV development
    
    ErrorLog "$logsDirectory/apache/error.log"
    CustomLog "$logsDirectory/apache/access.log" common
    
    Header unset ETag
    FileETag None
    
    <FilesMatch "\.(ico|pdf|flv|jpg|jpeg|png|gif|js|css|swf)(\.gz)?$$">
        ExpiresActive On
        ExpiresDefault "access plus 10 years"
        Header set Expires "Thu, 15 Apr 2020 20:00:00 GMT"
        Header unset ETag
        FileETag None
    </FilesMatch>

    <Directory "$installationPath/trunk/public">
        Options Indexes MultiViews FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
    
    <!-- 
    <Directory "$installationPath/trunk/public/css">
        ExpiresDefault "access plus 10 years"
        Options Indexes MultiViews FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
    
    <Directory "$installationPath/trunk/public/js">
        ExpiresDefault "access plus 10 years"
        Options Indexes MultiViews FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
    
    <Directory "$installationPath/trunk/public/images">
        Header set Expires "access plus 10 years"
        ExpiresDefault "access plus 10 years"
        Options Indexes MultiViews FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
    
    <Directory "$installationPath/trunk/public/uploads/image">
        Header set Expires "access plus 10 years"
        ExpiresDefault "access plus 10 years"
        Options Indexes MultiViews FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
    
    <Directory "$installationPath/trunk/public/uploads/thumb">
        Header set Expires "access plus 10 years"
        ExpiresDefault "access plus 10 years"
        Options Indexes MultiViews FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
    
    <Location "$installationPath/trunk/public">
        SetOutputFilter DEFLATE
        BrowserMatch ^Mozilla/4 gzip-only-text/html
        BrowserMatch ^Mozilla/4\.0[678] no-gzip
        BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
        SetEnvIfNoCase Request_URI \\.(?:gif|jpe?g|png)$ no-gzip dont-vary
        Header append Vary User-Agent env=!dont-vary
    </Location>
    -->
</VirtualHost>