<?php
namespace App\Interfaces;

interface BasePlayListInterface{
    public function add($idUser,array $data);

    public function edit(int $idPlayList);

    public function delete(string $idPlayList);

    public function getAllSong(int $idPlayList);

    public function store($idPlayList,array $data);

    public function getAllPlayList($idUser);
}