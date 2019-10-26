<?php

/***
 * @author: sa0ChunLuyu
 * @time: 2019/10/25 5:54 下午
 */
class toolController extends sa0Tool
{
    public function index()
    {
        $res = $this->pdo->createRow('users', array(
            'userNickname' => 'yo',
            'createTime' => time(),
        ));

        $this->json($res);
    }
}