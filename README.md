# Custom CVS Export

1. Créer une controller ExportCsvController.php
2. Ajouter les routes du fichier routing.php à votre application
3. Créer un dossier Services dans votre bundle (YourBundleName/Services) puis y ajouter le fichier CsvRepository.php
4. Référencer le service dans le service.yml de votre application (cf service.yml du dépot)
5. Créer une vue pour vos formulaires de sélection (cf form.html.twig)
6. Aller sur  YourApplicationPath/showTables
7. Générer le CSV


### A faire
1. Gérer le cas des classes enfant, pour le moment toutes les classes sont listées distinctement, si A étend B, on peut choisir soit A soit B, et B ne récupère pas les champs de A
  
    
      
         
*Testé sur Symfony 2.8*
