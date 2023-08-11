# Custom Logout and CPF Login Plugin

Este plugin WordPress oferece funcionalidades personalizadas de logoff (logout) e login com CPF. Ele permite que os usuários façam logoff imediatamente ao clicar em um hiperlink "Sair agora", redirecionando-os para a página inicial do site após o logoff. Além disso, o plugin também apresenta um formulário de login personalizado onde os usuários podem fazer login usando o número de CPF como identificação única, dispensando a necessidade de senha.

## Recursos

- Hiperlink "Sair agora" que efetua logoff imediatamente e redireciona para a página inicial.
- Formulário de login personalizado que permite aos usuários fazer login usando o número de CPF como identificação.
- Formatação automática do CPF inserido para melhor usabilidade.
- Redirecionamento automático após o login bem-sucedido usando o CPF.

## Instalação

1. Faça o download deste repositório como arquivo ZIP.
2. No painel de administração do WordPress, vá para "Plugins" e clique em "Adicionar novo".
3. Clique em "Carregar plugin" e selecione o arquivo ZIP baixado.
4. Ative o plugin "Custom Logout and CPF Login" após a instalação.

## Uso

- **Logoff Personalizado:** Use o shortcode `[sair-agora]` em qualquer página ou post para exibir o hiperlink "Sair agora" que efetua logoff e redireciona.
- **Login com CPF:** Use o shortcode `[cpf_sem_senha_login_form]` para incorporar o formulário de login personalizado em qualquer página ou post. Os usuários podem fazer login usando o número de CPF como identificação única.

Lembre-se de ajustar a URL de redirecionamento de logoff no código do plugin de acordo com as suas necessidades.

**Autor:** Atila Rodrigues
