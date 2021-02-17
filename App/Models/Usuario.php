<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model
{
    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($value)
    {
        return $this->$value;
    }

    public function __set($attr, $value)
    {
        $this->$attr = $value;
    }

    //Salvar
    public function salvar()
    {
        $query = "
            insert into usuarios(
                nome,email,senha
            )values(
                :nome,:email,:senha
        )";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':senha', $this->senha);

        $stmt->execute();

        return $this;
    }

    //Validar cadastro
    public function validarCadastro()
    {
        $valido = true;

        if (strlen($this->nome) < 4) {
            $valido = false;
        }
        if (strlen($this->email) < 3) {
            $valido = false;
        }
        if (strlen($this->senha) < 4) {
            $valido = false;
        }

        return $valido;
    }

    //Recuperar com o Usuario o E-mail
    public function getUsuarioEmail()
    {
        $query = "select 
                nome,email
            from 
                usuarios
            where
                email = :email
            ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Valida o nome
    public function getUsuarioNome()
    {
        $query = "select 
                nome,email
            from 
                usuarios
            where
                nome = :nome
            ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Autenticar o login
    public function autenticar()
    {
        $query = "
            select
                id,nome,email
            from
                usuarios
            where
                email = :email and senha = :senha
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':senha', $this->senha);

        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario['id'] != '' && $usuario['nome'] != '') {
            $this->id = $usuario['id'];
            $this->nome = $usuario['nome'];
        }

        return $this;
    }

    public function getAll()
    {
        $query = "
            select
                u.id, u.nome, u.email,
                (
                    select
                        count(*)
                    from
                        usuarios_seguindo as us
                    where
                        us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
                    
                ) as seguindo_sn
            from
                usuarios as u
            where
                u.nome like :nome and u.id != :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%' . $this->nome . '%');
        $stmt->bindValue(':id_usuario', $this->id);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function seguirUsuario($id_usuario_seguindo)
    {
        $query = "
            insert into usuarios_seguindo(
                id_usuario, id_usuario_seguindo
            )values(
                :id_usuario, :id_usuario_seguindo
        )";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->id);
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $stmt->execute();

        return true;
    }

    public function deixarSeguirUsuario($id_usuario_seguindo)
    {
        $query = "
            delete from
                usuarios_seguindo
            where
                id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->id);
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $stmt->execute();

        return true;
    }

    //Recuperar informações do usuário
    public function getInfoUser()
    {
        $query = "
            select
                nome
            from
                usuarios
            where
                id = :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->id);

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //Recuperar o total de tweets
    public function getInfoTweetsUser()
    {
        $query = "
            select
                count(*) as total_tweet
            from
                tweets
            where
                id_usuario = :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->id);

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //Recuperar o total de usuários seguindo
    public function getInfoSeguindoUser()
    {
        $query = "
            select
                count(*) as total_seguindo
            from
                usuarios_seguindo
            where
                id_usuario = :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->id);

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //Recuperar o total de seguidores
    public function getInfoSeguidoresUser()
    {
        $query = "
            select
                count(*) as total_seguidores
            from
                usuarios_seguindo
            where
                id_usuario_seguindo = :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->id);

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
