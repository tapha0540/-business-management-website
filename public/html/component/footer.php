<style>
    .dashboard-footer {
        color: var(--text-dark-color);
        padding: 2rem 2rem 1.25rem;
        margin-top: auto;
        border-top: 2px solid #ff4d00;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        margin-bottom: 1.5rem;
    }

    .footer-section h6 {
        color: #ff4d00;
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .footer-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-section li {
        margin-bottom: 0.75rem;
    }

    .footer-section a {
        color: var(--text-dark-color);
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .footer-section a:hover {
        color: #ff4d00;
        transform: translateX(4px);
    }

    .footer-divider {
        border-top: 1px solid #333;
        margin: 1rem 0;
    }

    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .footer-copyright {
        font-size: 0.85rem;
    }

    .footer-copyright strong {
        color: #ff4d00;
    }

    .footer-socials {
        display: flex;
        gap: 1rem;
    }

    .footer-socials a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: rgba(255, 77, 0, 0.1);
        border-radius: 50%;
        color: #ff4d00;
        transition: all 0.2s ease;
        font-size: 1rem;
    }

    .footer-socials a:hover {
        background: #ff4d00;
        color: white;
        transform: translateY(-2px);
    }

    .footer-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #10b981;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    @media (max-width: 1024px) {
        .footer-content {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .dashboard-footer {
            padding: 1.5rem 1.5rem 1rem;
        }
    }

    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
        }

        .footer-socials {
            justify-content: center;
        }

        .dashboard-footer {
            padding: 1.5rem 1rem 0.75rem;
        }
    }
</style>

<footer class="card dashboard-footer bg-lighter">
    <div class="footer-content">
        <div class="footer-section">
            <h6><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 21h18"></path><path d="M5 21V7l7-4 7 4v14"></path><path d="M9 10h6"></path><path d="M9 14h6"></path></svg></span>À Propos</h6>
            <ul>
                <li><a href="#">Qui sommes-nous ?</a></li>
                <li><a href="#">Notre histoire</a></li>
                <li><a href="#">Nos valeurs</a></li>
                <li><a href="#">Carrières</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h6><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M21 8a2 2 0 0 0-2-2H5l-2 4v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z"></path><path d="M3 10h18"></path></svg></span>Produits</h6>
            <ul>
                <li><a href="#">Gestion des stocks</a></li>
                <li><a href="#">Catalogue</a></li>
                <li><a href="#">Tarifs</a></li>
                <li><a href="#">Fonctionnalités</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h6><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></span>Support</h6>
            <ul>
                <li><a href="#">Centre d'aide</a></li>
                <li><a href="#">Documentation</a></li>
                <li><a href="#">Contact support</a></li>
                <li><a href="#">FAQ</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h6><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3v18"></path><path d="M5 7h14"></path><path d="M6 17h12"></path><path d="M8 7c0 4-1 6-3 10"></path><path d="M16 7c0 4 1 6 3 10"></path></svg></span>Légal</h6>
            <ul>
                <li><a href="#">Conditions d'utilisation</a></li>
                <li><a href="#">Politique de confidentialité</a></li>
                <li><a href="#">Cookies</a></li>
                <li><a href="#">Mentions légales</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-divider"></div>

    <div class="footer-bottom">
        <div class="footer-copyright">
            © 2026 <strong>Gestion Commerciale</strong> - Tous droits réservés
        </div>

        <div class="footer-status">
            <span class="status-dot"></span>
            Tous nos services sont en ligne
        </div>

        <div class="footer-socials">
            <a href="#" title="Facebook">f</a>
            <a href="#" title="Twitter">𝕏</a>
            <a href="#" title="LinkedIn">in</a>
            <a href="#" title="Instagram">📷</a>
        </div>
    </div>
</footer>