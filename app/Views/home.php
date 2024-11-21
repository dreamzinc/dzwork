<?php $this->extend('layouts/app'); ?>

<?php $this->section('styles'); ?>
<style>
    .header {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        margin: -3rem -3rem 2rem -3rem;
        padding: 2rem;
        position: relative;
    }

    h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
        background: rgba(255, 255, 255, 0.95);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
        color: rgba(255, 255, 255, 0.9);
    }

    .version {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 9999px;
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.9);
        margin: 1rem 0;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .feature-section {
        margin: 2rem 0;
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }

    .feature-section:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    .links {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 2rem;
    }

    .link {
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.75rem;
        color: #fff;
        text-decoration: none;
        transition: all 0.2s ease;
        backdrop-filter: blur(4px);
        position: relative;
        overflow: hidden;
    }

    .link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0) 100%);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }

    .link:hover::before {
        transform: translateX(100%);
    }

    .link:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .header {
            margin: -2rem -1.5rem 1.5rem -1.5rem;
            padding: 1.5rem;
        }

        h1 {
            font-size: 2rem;
        }

        .links {
            flex-direction: column;
        }

        .link {
            width: 100%;
            text-align: center;
        }

        .feature-section {
            margin: 1.5rem 0;
        }
    }
</style>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="header">
    <h1>Selamat Datang di DzWork</h1>
    <p class="subtitle">Framework PHP Modern</p>
    <div class="version">Versi 1.0</div>
</div>

<div class="feature-section">
    <p>
        Rasakan kekuatan kesederhanaan dengan DzWork - framework PHP yang ringan dan modern,
        dirancang untuk pengembang yang menghargai kode bersih dan kinerja yang efisien.
    </p>
</div>

<div class="links">
    <a href="https://github.com/yourusername/dzwork" class="link" target="_blank">Dokumentasi</a>
    <a href="https://github.com/yourusername/dzwork" class="link" target="_blank">GitHub</a>
    <a href="https://github.com/yourusername/dzwork/issues" class="link" target="_blank">Bantuan</a>
</div>
<?php $this->endSection(); ?>
