<?php
// usuarioController.php
// Controlador para gestionar las acciones de los usuarios

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    // Listar usuarios
    public function listar()
    {
        try {
            $usuarioModel = new Usuario();
            $usuarios = $usuarioModel->listar();
            return [
                'success' => true,
                'mensaje' => "Usuarios listados exitosamente.",
                'data' => $usuarios
            ];
        } catch (Exception $e) {
            error_log("Error al listar los usuarios: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => "Error al obtener la lista de usuarios."
            ];
        }
    }

    // Registrar un nuevo usuario
    public function registrar($data)
    {
        // Validar los datos
        if (empty($data['nombre_usuario']) || empty($data['password']) || empty($data['tipo_usuario']) || empty($data['correo_electronico'])) {
            return [
                'success' => false,
                'mensaje' => "El nombre de usuario, contraseña, tipo de usuario y correo electrónico son requeridos."
            ];
        }

        // Validar tipo de usuario
        if (!in_array($data['tipo_usuario'], ["admin", "limitado"])) {
            return [
                'success' => false,
                'mensaje' => "El tipo de usuario no es válido."
            ];
        }

        try {
            // Crear un nuevo objeto Usuario
            $usuarioModel = new Usuario();
            $resultado = $usuarioModel->registrar($data['nombre_usuario'], $data['password'], $data['tipo_usuario'], $data['correo_electronico']);
            
            if ($resultado['success']) {
                return [
                    'success' => true,
                    'mensaje' => "Usuario registrado exitosamente."
                ];
            } else {
                return [
                    'success' => false,
                    'mensaje' => $resultado['mensaje']
                ];
            }
        } catch (Exception $e) {
            error_log("Error al registrar el usuario: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => "Error al registrar el usuario."
            ];
        }
    }

    // Obtener un usuario por ID
    public function obtener($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return [
                'success' => false,
                'mensaje' => "ID inválido."
            ];
        }

        try {
            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->obtener($id);

            if ($usuario) {
                return [
                    'success' => true,
                    'mensaje' => "Usuario obtenido exitosamente.",
                    'data' => $usuario
                ];
            } else {
                return [
                    'success' => false,
                    'mensaje' => "Usuario no encontrado."
                ];
            }
        } catch (Exception $e) {
            error_log("Error al obtener el usuario: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => "Error al obtener el usuario."
            ];
        }
    }

    // Editar usuario
    public function editar($id, $data)
    {
        // Validar los datos
        if (empty($id) || !is_numeric($id)) {
            return [
                'success' => false,
                'mensaje' => "ID inválido."
            ];
        }

        if (empty($data['nombre_usuario']) || empty($data['password']) || empty($data['tipo_usuario']) || empty($data['correo_electronico'])) {
            return [
                'success' => false,
                'mensaje' => "El nombre de usuario, contraseña, tipo de usuario y correo electrónico son requeridos."
            ];
        }

        if (!in_array($data['tipo_usuario'], ["admin", "limitado"])) {
            return [
                'success' => false,
                'mensaje' => "El tipo de usuario no es válido."
            ];
        }

        try {
            // Crear un nuevo objeto Usuario
            $usuarioModel = new Usuario();
            $resultado = $usuarioModel->editar($id, $data['nombre_usuario'], $data['password'], $data['tipo_usuario'], $data['correo_electronico']);

            if ($resultado['success']) {
                return [
                    'success' => true,
                    'mensaje' => "Usuario actualizado exitosamente."
                ];
            } else {
                return [
                    'success' => false,
                    'mensaje' => $resultado['mensaje']
                ];
            }
        } catch (Exception $e) {
            error_log("Error al editar el usuario: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => "Error al editar el usuario."
            ];
        }
    }

    // Eliminar usuario
    public function eliminar($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return [
                'success' => false,
                'mensaje' => "ID inválido."
            ];
        }

        try {
            $usuarioModel = new Usuario();
            if ($usuarioModel->eliminar($id)) {
                return [
                    'success' => true,
                    'mensaje' => "Usuario eliminado exitosamente."
                ];
            } else {
                return [
                    'success' => false,
                    'mensaje' => "No se pudo eliminar el usuario."
                ];
            }
        } catch (Exception $e) {
            error_log("Error al eliminar el usuario: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => "Error al eliminar el usuario."
            ];
        }
    }
}
