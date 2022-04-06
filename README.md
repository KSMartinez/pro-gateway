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



