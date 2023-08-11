<?php
/*
Plugin Name: Custom Logout and CPF Login
Description: Plugin para adicionar um shortcode de logoff personalizado e login com CPF.
*/

// Cria uma página de login personalizada
function criar_pagina_login() {
    // Verifica se a página já existe antes de criá-la
    $pagina_login = get_page_by_path('login-cpf');
    if (!$pagina_login) {
        // Cria uma nova página de login
        $pagina_id = wp_insert_post(array(
            'post_title'    => 'Login CPF',
            'post_content'  => '[cpf_sem_senha_login_form]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'login-cpf',
        ));

        // Adiciona meta-dados para identificar a página de login
        update_post_meta($pagina_id, '_cpf_sem_senha_login_page', '1');
    }
}

// Redireciona para a página de login personalizada quando necessário
function redirecionar_login() {
    // Verifica se o usuário não está logado e se está tentando acessar uma página protegida
    if (!is_user_logged_in() && is_page() && !is_admin()) {
        global $post;
        $pagina_id = $post->ID;
        $is_login_page = get_post_meta($pagina_id, '_cpf_sem_senha_login_page', true);

        // Redireciona apenas se a página atual for a página de login personalizada
        if ($is_login_page && !is_page('login-cpf')) {
            wp_redirect(home_url('/login-cpf'));
            exit;
        }
    }
}

// Função para exibir o formulário de login personalizado com estilo melhorado e formatação automática do CPF
function cpf_sem_senha_login_form() {
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';

        // Remove caracteres especiais do CPF
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Valida o formato do CPF
        if (strlen($cpf) !== 11) {
            echo '<div class="cpf-sem-senha-error">CPF inválido. Por favor, insira um CPF válido com 11 dígitos.</div>';
            return;
        }

        // Formata o CPF
        $cpf_formatado = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);

        // Verifica se o CPF existe no banco de dados dos usuários
        $user = get_users(array('meta_key' => 'cpf', 'meta_value' => $cpf, 'number' => 1));
        if ($user) {
            // Realiza o login do usuário com o CPF encontrado
            wp_clear_auth_cookie();
            wp_set_current_user($user[0]->ID);
            wp_set_auth_cookie($user[0]->ID);

            // Redireciona para a página "/candidato"
            wp_redirect(home_url('/candidato'));
            exit;
        } else {
            // Exibe mensagem de erro
            echo '<div class="cpf-sem-senha-error">CPF inválido. Por favor, tente novamente.</div>';
        }
    }

    // Exibe o formulário de login personalizado com estilo
    ?>
    <style>
        .cpf-sem-senha-form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .cpf-sem-senha-form {
            max-width: 300px;
        }

        .cpf-sem-senha-form label,
        .cpf-sem-senha-form input[type="text"] {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        .cpf-sem-senha-form input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .cpf-sem-senha-form input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #008815;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .cpf-sem-senha-error {
            color: #ff0000;
            margin-top: 10px;
        }
		.wp-site-blocks {
		display: none !important;
		}
    </style>

    <script>
        // Função para formatar o CPF automaticamente
        function formatarCPF(campo) {
            var valor = campo.value.replace(/\D/g, '');

            // Verifica se o valor está vazio
            if (!valor) {
                campo.value = '';
                return;
            }

            // Formata o CPF
            var formatado = valor.substr(0, 3) + '.' + valor.substr(3, 3) + '.' + valor.substr(6, 3) + '-' + valor.substr(9);
            campo.value = formatado;
        }
    </script>

    <div class="cpf-sem-senha-form-wrapper">
        <div class="cpf-sem-senha-form">
            <form id="cpf-sem-senha-login-form" method="POST" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
                <input type="text" name="cpf" id="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" title="Insira um CPF válido no formato xxx.xxx.xxx-xx" required oninput="formatarCPF(this)" placeholder="CPF">
                <input type="submit" value="Entrar">
            </form>
        </div>
    </div>

    <?php
}

// Função para fazer o logout e redirecionar o usuário
function sair_agora() {
    ob_start();
    $redirect_url = 'http://vestibular.unicathedral.edu.br'; // URL de redirecionamento

    ?>
    <a href="<?php echo wp_logout_url($redirect_url); ?>" onclick="event.preventDefault(); document.getElementById('custom-logout-form').submit();">Sair</a>
    <form id="custom-logout-form" action="<?php echo wp_logout_url($redirect_url); ?>" method="POST" style="display: none;"></form>
    <?php
    return ob_get_clean();
}

// Registra os ganchos de ação necessários
add_action('init', 'criar_pagina_login');
add_action('template_redirect', 'redirecionar_login');
add_shortcode('cpf_sem_senha_login_form', 'cpf_sem_senha_login_form');
add_shortcode('sair-agora', 'sair_agora');
