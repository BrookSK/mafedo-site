<?php

declare(strict_types=1);

/**
 * Biblioteca de ícones SVG de linha (stroke currentColor).
 *
 * Uso: echo icon('construcao');  ou  icon('whatsapp', 'minha-classe')
 * Todos os ícones usam viewBox 0 0 24 24, traço fino e herdam a cor via
 * currentColor — então basta definir color/width/height no CSS do contexto.
 */

if (!function_exists('icon')) {
    function icon(string $name, string $class = ''): string
    {
        $paths = icon_library();
        $inner = $paths[$name] ?? $paths['default'];
        $cls = $class !== '' ? ' class="' . e($class) . '"' : '';

        return '<svg' . $cls . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" '
            . 'stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" '
            . 'aria-hidden="true" focusable="false">' . $inner . '</svg>';
    }
}

if (!function_exists('service_icon_name')) {
    /**
     * Escolhe o ícone adequado para um serviço a partir do slug/título.
     */
    function service_icon_name(string $slugOrTitle): string
    {
        $s = strtolower($slugOrTitle);
        return match (true) {
            str_contains($s, 'constru')                      => 'construcao',
            str_contains($s, 'reforma') || str_contains($s, 'retrofit') => 'reforma',
            str_contains($s, 'manuten')                      => 'manutencao',
            str_contains($s, 'instala')                      => 'instalacoes',
            str_contains($s, 'gerenc') || str_contains($s, 'gest') => 'gerenciamento',
            str_contains($s, 'projeto') || str_contains($s, 'regulariz') || str_contains($s, 'consultoria') => 'projeto',
            default                                          => 'construcao',
        };
    }
}

if (!function_exists('icon_library')) {
    function icon_library(): array
    {
        return [
            // ---- Serviços / engenharia ----
            // Construção: guindaste/estrutura
            'construcao'   => '<path d="M3 21h18"/><path d="M6 21V8l9-3v16"/><path d="M15 21V9l3 1v11"/><path d="M9 9h.01M9 13h.01M9 17h.01"/>',
            // Reforma: espátula/rolo
            'reforma'      => '<path d="M3 21h18"/><path d="M14 3l7 7-4 1-4-4 1-4z"/><path d="M14 7L4 17v3h3L17 10"/>',
            // Manutenção: chave de boca
            'manutencao'   => '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.2L4 16.8 7.2 20l5.3-5.3a4 4 0 0 0 5.2-5.4l-2.5 2.5-2.3-.6-.6-2.3 2.4-2.6z"/>',
            // Instalações: raio/tomada (elétrica + hidráulica)
            'instalacoes'  => '<path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z"/>',
            // Gerenciamento: prancheta/checklist
            'gerenciamento'=> '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1"/><path d="M9 10l1.5 1.5L13 9"/><path d="M9 16h6"/>',
            // Projeto: compasso/planta
            'projeto'      => '<path d="M12 3v6"/><path d="M12 9l-5 11"/><path d="M12 9l5 11"/><circle cx="12" cy="4" r="1.4"/>',

            // ---- Diferenciais ----
            'people'       => '<circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.2"/><path d="M3 20a6 6 0 0 1 12 0"/><path d="M15.5 14.5A5 5 0 0 1 21 20"/>',
            'gear'         => '<circle cx="12" cy="12" r="3.2"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M19.1 4.9L17 7M7 17l-2.1 2.1"/>',
            'clock'        => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            'shield'       => '<path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3z"/><path d="M9 12l2 2 4-4"/>',

            // ---- Contato ----
            'phone'        => '<path d="M4 4h4l2 5-2.5 1.5a11 11 0 0 0 6 6L15 14l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 2 6a2 2 0 0 1 2-2z"/>',
            'mail'         => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
            'whatsapp'     => '<path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4.1A8 8 0 1 1 20 11.5z"/><path d="M9 9c0 3 2.5 5.5 5.5 5.5"/><path d="M9 9c0-.6.4-1 1-1l1 2-1 1"/><path d="M14.5 14.5c.6 0 1-.4 1-1l-2-1-1 1"/>',
            'pin'          => '<path d="M12 21s-7-6.3-7-11a7 7 0 0 1 14 0c0 4.7-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>',
            'clock2'       => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',

            // ---- Redes sociais ----
            'instagram'    => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1.1" fill="currentColor" stroke="none"/>',
            'facebook'     => '<path d="M15 3h-2.5A3.5 3.5 0 0 0 9 6.5V9H6v3h3v9h3v-9h2.5l.5-3H12V6.8c0-.6.4-.8.9-.8H15V3z"/>',
            'linkedin'     => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M8 10v7"/><path d="M8 7v.01"/><path d="M12 17v-4a2 2 0 0 1 4 0v4"/><path d="M12 13v4"/>',
            'youtube'      => '<rect x="3" y="6" width="18" height="12" rx="3"/><path d="M11 9.5l4 2.5-4 2.5z" fill="currentColor" stroke="none"/>',

            // ---- Genéricos ----
            'arrow'        => '<path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>',
            'check'        => '<path d="M20 6L9 17l-5-5"/>',
            'default'      => '<circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 2"/>',
        ];
    }
}
