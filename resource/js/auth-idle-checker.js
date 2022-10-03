class AuthIdleChecker {
    constructor() {
        this.requestTime = new Date();
        const body = document.getElementsByTagName('body')[0];

        this.maxIdleTime = parseInt(body?.getAttribute('data-max-idle-time')) || 1200;
        this.authPollInterval = parseInt(body?.getAttribute('data-auth-poll-interval')) || 60;

        this.init();

        this.schedule();
    }

    init() {
        const buttonAlive = document.getElementById('authIdleCheckerWarningAlive');
        buttonAlive?.addEventListener('click', () => {
            fetch('/auth/idle-alive', {method: 'post'})
                .then((response) => {
                    if (!response.ok) {
                        throw new Error();
                    }

                    return response.json();
                })
                .then((data) => {
                    this.requestTime = new Date();

                    this.toggleIdleLogoutWarning(false);

                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                });
        });

        const buttonIgnore = document.getElementById('authIdleCheckerWarningIgnore');
        buttonIgnore?.addEventListener('click', () => {
            this.toggleIdleLogoutWarning(false);
        });
    };

    schedule() {
        setTimeout(() => {
            this.check();
            this.schedule();
        }, this.authPollInterval * 1000);
    };

    check() {
        fetch('/auth/idle-poll')
            .then((response) => {
                if (!response.ok) {
                    throw new Error();
                }

                return response.json();
            })
            .then((data) => {
                if (data.show_idle_logout_warning) {
                    this.toggleIdleLogoutWarning(true);
                }

                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(() => {
                // Fallback in case of e.g. network failure
                if ((+ new Date()) - (+this.requestTime) > this.maxIdleTime * 1000) {
                    window.location.href = '/';
                }
            });
    };

    toggleIdleLogoutWarning(show) {
        const warning = document.getElementById('authIdleCheckerWarning');
        if (warning) {
            if (show) {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }
    };
}

window.addEventListener('load', () => {
    const authIdleChecker = new AuthIdleChecker();
});
