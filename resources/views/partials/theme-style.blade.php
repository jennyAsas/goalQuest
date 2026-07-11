<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=EB+Garamond:wght@400;500;600&family=Cinzel:wght@600&display=swap');

    :root {
        --bg-deep: #241511;
        --bg-panel: #2E1B15;
        --bg-panel-2: #3A231B;
        --maroon-bright: #8C2A2A;
        --gold: #C9A227;
        --gold-soft: #E4C567;
        --parchment: #EDE0C8;
        --text-muted: #B8A48C;
        --sage: #7C8B5E;
        --border: rgba(228, 197, 103, 0.16);
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background:
            radial-gradient(ellipse at top, rgba(140, 42, 42, .18), transparent 60%),
            radial-gradient(ellipse at bottom, rgba(124, 139, 94, .08), transparent 55%),
            var(--bg-deep);
        color: var(--parchment);
        font-family: 'EB Garamond', serif;
        font-size: 16px;
        min-height: 100vh;
    }

    h1,
    h2,
    h3 {
        font-family: 'Cormorant Garamond', serif;
        margin: 0;
    }

    a {
        color: var(--gold-soft);
    }

    .wrap {
        max-width: 1080px;
        margin: 0 auto;
        padding: 32px 20px 80px;
    }

    /* Buttons */
    .btn {
        font-family: 'EB Garamond', serif;
        font-weight: 600;
        font-size: 14px;
        border: none;
        border-radius: 5px;
        padding: 9px 17px;
        cursor: pointer;
        display: inline-block;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--gold);
        color: #2A1B04;
    }

    .btn-ghost {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--parchment);
    }

    .btn-rose {
        background: rgba(140, 42, 42, .25);
        color: #E8A5A5;
        border: 1px solid rgba(140, 42, 42, .5);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 4px;
    }

    /* Form fields */
    .field-label {
        font-size: 12px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 5px;
        font-family: 'Cinzel', serif;
        letter-spacing: .02em;
    }

    .field-input {
        width: 100%;
        background: var(--bg-panel-2);
        border: 1px solid var(--border);
        border-radius: 4px;
        padding: 9px 10px;
        color: var(--parchment);
        font-family: 'EB Garamond', serif;
        font-size: 15px;
    }

    .field-input:focus {
        outline: 2px solid var(--gold);
        outline-offset: 1px;
    }

    .field-error {
        color: #E8A5A5;
        font-size: 12.5px;
        margin: 6px 0 0;
        padding: 0;
        list-style: none;
    }

    .field-checkbox {
        accent-color: var(--gold);
        width: 16px;
        height: 16px;
    }

    .authcard label {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .authcard form>div {
        margin-bottom: 18px;
    }

    .status-msg {
        background: var(--bg-panel-2);
        border: 1px solid var(--gold);
        padding: 10px 14px;
        border-radius: 6px;
        font-style: italic;
        font-size: 14px;
        margin-bottom: 16px;
    }

    /* Guest pages (login/register/etc) */
    .authbody {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 32px 16px;
    }

    .authlogo {
        margin-bottom: 24px;
        text-decoration: none;
    }

    .applogo {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gold-soft);
        font-family: 'Cormorant Garamond', serif;
        font-size: 22px;
        font-weight: 600;
    }

    .authcard {
        background: var(--bg-panel);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 32px;
        width: 100%;
        max-width: 420px;
    }

    /* Nav (dashboard/calendar/profile) */
    .questnav {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 22px;
    }

    .questnav a {
        font-family: 'Cinzel', serif;
        font-size: 12.5px;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--text-muted);
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 5px;
        border: 1px solid transparent;
    }

    .questnav a:hover {
        color: var(--parchment);
    }

    .questnav a.active {
        color: var(--gold-soft);
        border-color: var(--border);
        background: var(--bg-panel);
    }

    .questnav form {
        margin-left: auto;
    }

    .navlogout {
        background: none;
        border: 1px solid var(--border);
        color: var(--text-muted);
        font-family: 'Cinzel', serif;
        font-size: 12.5px;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 8px 16px;
        border-radius: 5px;
        cursor: pointer;
    }

    .navlogout:hover {
        color: var(--parchment);
        border-color: var(--gold);
    }

    .flash {
        background: var(--bg-panel-2);
        border: 1px solid var(--gold);
        padding: 12px 18px;
        border-radius: 6px;
        font-style: italic;
        margin-bottom: 20px;
    }

    /* Modal (used by "delete account") */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(20, 10, 7, .75);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
        padding: 20px;
    }

    .modal {
        background: var(--bg-panel);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 26px;
        max-width: 420px;
        width: 100%;
    }

    /* Profile page panels */
    .panel {
        background: var(--bg-panel);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 20px;
    }
</style>
