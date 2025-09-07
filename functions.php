<?php
require_once __DIR__.'/config.php';

function create_ticket($title,$description,$category,$priority){
  global $pdo;
  $sql="INSERT INTO tickets(title,description,category,priority) VALUES(?,?,?,?)";
  return $pdo->prepare($sql)->execute([$title,$description,$category,$priority]);
}
function update_ticket($id,$title,$description,$category,$priority){
  global $pdo;
  $sql="UPDATE tickets SET title=?,description=?,category=?,priority=? WHERE id=?";
  return $pdo->prepare($sql)->execute([$title,$description,$category,$priority,$id]);
}
function toggle_status($id){
  global $pdo;
  $pdo->prepare("UPDATE tickets SET status = IF(status='Ouvert','Résolu','Ouvert') WHERE id=?")->execute([$id]);
}
function delete_ticket($id){
  global $pdo; $pdo->prepare("DELETE FROM tickets WHERE id=?")->execute([$id]);
}
function get_ticket($id){
  global $pdo; $st=$pdo->prepare("SELECT * FROM tickets WHERE id=?"); $st->execute([$id]); return $st->fetch();
}
function list_tickets($q='',$cat='',$prio='',$stat=''){
  global $pdo;
  $w=[]; $p=[];
  if($q!==''){ $w[]="title LIKE ?"; $p[]="%$q%"; }
  if($cat!==''){ $w[]="category=?"; $p[]=$cat; }
  if($prio!==''){ $w[]="priority=?"; $p[]=$prio; }
  if($stat!==''){ $w[]="status=?"; $p[]=$stat; }
  $where = $w ? ('WHERE '.implode(' AND ',$w)) : '';
  $sql="SELECT * FROM tickets $where ORDER BY created_at DESC";
  $st=$pdo->prepare($sql); $st->execute($p); return $st->fetchAll();
}
