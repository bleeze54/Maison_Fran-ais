<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Panel Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            padding-top: 180px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            padding: 15px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Panel Administrateur</h1>
        <div class="button-grid">
            <a href="/admin/users" class="btn">Utilisateurs</a>
            <a href="/admin/orders" class="btn">Commandes</a>
            <a href="/admin/products" class="btn">Produits</a>
            <a href="/admin/reductions" class="btn">Reduction</a>
            <a href="/admin/exclusivites" class="btn">Exclusivité</a>
            <a href="/admin/tailles" class="btn">Stocks Produits</a>
            <a href="/admin/usertokens" class="btn">token de connexion</a>
            <a href="/admin/tickets" class="btn">Tickets Support</a>
        </div>
    </div>
</body>
</html>