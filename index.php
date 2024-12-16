<?php
// Inclure la connexion à la base de données
require 'database.php';

// Ajouter une tâche
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $title = $_POST['title'];
    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (title) VALUES (:title)");
        $stmt->execute(['title' => $title]);
    }
}

// Supprimer une tâche
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

// Mettre à jour une tâche
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    if (!empty($id) && !empty($title)) {
        $stmt = $pdo->prepare("UPDATE tasks SET title = :title WHERE id = :id");
        $stmt->execute(['title' => $title, 'id' => $id]);
    }
}

// Récupérer toutes les tâches
$stmt = $pdo->prepare("SELECT * FROM tasks ORDER BY created_at DESC");
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application CRUD - Tâches</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .task {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .task-actions {
            display: flex;
            gap: 10px;
        }

        button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button.delete {
            background-color: red;
            color: white;
        }

        button.edit {
            background-color: blue;
            color: white;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 5px;
            width: 300px;
            margin-right: 10px;
        }

        .edit-form {
            display: none;
            /* Les formulaires sont masqués par défaut */
            margin-top: 10px;
            gap: 10px;
        }
    </style>
</head>

<body>
    <h1>Gestion des Tâches</h1>

    <!-- Formulaire pour ajouter une tâche -->
    <form method="POST">
        <input type="text" name="title" placeholder="Nouvelle tâche" required>
        <button type="submit" name="add_task">Ajouter</button>
    </form>

    <!-- Liste des tâches -->
    <?php foreach ($tasks as $task): ?>
        <div class="task">
            <span><?= htmlspecialchars($task['title']); ?></span>
            <div class="task-actions">
                <a href="?delete=<?= $task['id']; ?>">
                    <button class="delete">Supprimer</button>
                </a>

                <!-- Bouton pour afficher le formulaire d'édition -->
                <?php var_dump($task['id']); ?> <!-- Vérifiez que l'ID de la tâche est bien généré -->
                <button class="edit" onclick="toggleEditForm(<?= $task['id']; ?>)">Modifier</button>
            </div>
        </div>
        <!-- Formulaire pour éditer la tâche -->
        <form method="POST" class="edit-form" id="edit-form-<?= $task['id']; ?>">
            <input type="hidden" name="id" value="<?= $task['id']; ?>">
            <input type="text" name="title" value="<?= htmlspecialchars($task['title']); ?>" required>
            <button type="submit" name="update_task">Enregistrer</button>
        </form>
    <?php endforeach; ?>

    <script>
        // Fonction pour afficher/masquer le formulaire d'édition
        function toggleEditForm(taskId) {
            const form = document.getElementById(`edit-form-${taskId}`);
            // Vérifier si le formulaire est déjà affiché
            form.style.display = 'flex';
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'flex'; // Afficher le formulaire
            }
        }
    </script>
</body>

</html>