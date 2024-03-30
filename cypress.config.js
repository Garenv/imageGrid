const fs = require('fs');

module.exports = {
    e2e: {
        baseUrl: 'http://phopixel.test',
        setupNodeEvents(on, config) {
            on('task', {
                readFileMaybe(filename) {
                    if (fs.existsSync(filename)) {
                        return fs.readFileSync(filename, 'utf8');
                    }
                    return null;
                },
            });
        },
        supportFile: './cypress/support/index.js',
        viewportWidth: 1366,
        viewportHeight: 768,
        video: true
    },
};
