## Reseau Pro - Portail Univesite 

- This project has PHPStan installed for code analysis. You can run the analysis at any point using 
``` 
composer stan
```

- Please copy the file *pre-commit* from *git-hooks* directory and paste it in your *.git/hooks* directory. This will ensure that the analysis is run every time you make a commit. 

- If you need to commit something without the analysis, you can bypass it by using the --no-verify flag like this.
```
git commit -m "commit message" --no-verify
```

- The database is available as a docker container.  
> **The database is not persistant. You will lose all data once you stop the container**. We use fixtures to generate data anyway.

To setup the database, do the following:

To create the container
``` docker compose up -d ```

To create the database
``` symfony console doctrine:database:create ```

To create tables
``` symfony console doctrine:migrations:migrate ```

To load sample data to the tables 
``` symfony console doctrine:fixtures:load ```

