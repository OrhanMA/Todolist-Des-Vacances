<?php

class DBManager
{
  private $bdd;
  public function __construct()
  {
    try {
      $this->bdd = new PDO(
        'mysql:host=localhost;dbname=vacances;charset=utf8',
        'phpmyadmin',
        'root'
      );
    } catch (Exception $e) {
      die('Erreur : ' . $e->getMessage());
    }
  }

  public function getConnexion()
  {
    return $this->bdd;
  }
}
class Task
{
  private $task_name;
  private $id;
  public function getID()
  {
    return $this->id;
  }
  public function setID($id)
  {
    return $this->id = $id;
  }
  public function getTaskName()
  {
    return $this->task_name;
  }
  public function setTaskName($task_name)
  {
    return $this->task_name = $task_name;
  }
}

class ManagerTask extends DBManager
{
  public function getAll()
  {
    $res = $this->getConnexion()->query('SELECT * FROM task');

    $taskList = [];

    foreach ($res as $task) {
      $newTask = new Task();
      $newTask->setID($task['id']);
      $newTask->setTaskName($task['task_name']);
      $taskList[] = $newTask;
    }
    return $taskList;
  }

  public function create($task)
  {
    $request = 'INSERT INTO task (id, task_name) VALUE (?, ?)';
    $query = $this->getConnexion()->prepare($request);

    $query->execute([
      $task->getID(),
      $task->getTaskName()
    ]);
    header('Refresh:0');
  }

  public function delete($taskID)
  { {
      $request = 'DELETE FROM task WHERE id = ' . $taskID;
      $query = $this->getConnexion()->prepare($request);
      $query->execute();
      header('Location:index.php');
      exit();
    }
  }

  public function edit($taskID, $newTaskName)
  {
    $request = 'UPDATE `task` SET `task_name` = ? WHERE id = ' . $taskID;
    $query = $this->getConnexion()->prepare($request);
    $query->execute([$newTaskName]);
    header('Location:index.php');
    exit();
  }
}

$managerTask = new ManagerTask();
$allTasks = $managerTask->getAll();
if (isset($_GET['delete'])) {
  $managerTask->delete(intval($_GET['delete']));
}
if (!empty($_POST['taskName'])) {
  $newTask = new Task();
  $newTask->setTaskName($_POST['taskName']);
  $managerTask->create($newTask);
}
if (!empty($_POST['newTaskName'])) {
  $managerTask->edit(intval($_POST['newTaskID']), $_POST['newTaskName']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todolist des Vacances</title>
  <link rel="stylesheet" type="text/css" href="styles.css">

</head>

<body>
  <table class="table">
    <thead id="top-tr">
      <tr>
        <th>ID</th>
        <th>Task name</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($allTasks as $task) {

        $removeUrl = '?delete=' . $task->getID();
        $removeLink = '<a href="' . $removeUrl . '">Delete</a>';
        echo ('<tr>');
        echo ('<td>' . $task->getID() . '</td>');
        echo ('<td>' . $task->getTaskName() . '</td>');
        echo ('<td class="deleteBtn">' . $removeLink . '</td>');
        echo ('</tr>');
      }
      ?>

    </tbody>
  </table>

  <div>

    <form action="./index.php" method="POST">
      <div>
        <label for="taskName">Name:</label>
        <input type="text" name="taskName" id="taskName">
      </div>
      <input type="submit" value="ADD">
    </form>

    <form action="./index.php" method="post">
      <div>
        <label for="newTaskID">Edit:</label>
        <select name="newTaskID" id="newTaskID">
          <?php
          foreach ($allTasks as $task) {
            echo ('<option value="' . $task->getID() . '">' . $task->getTaskName() . '</option>');
          }
          ?>
        </select>
      </div>
      <div>
        <label for="newTaskName">new Task name:</label>
        <input type="text" name="newTaskName" id="newTaskName">
      </div>
      <input type="submit" value="EDIT">
    </form>
  </div>
</body>

</html>