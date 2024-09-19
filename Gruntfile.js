module.exports = function(grunt) {

    'use strict';

    // Project configuration
    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        addtextdomain: {
            options: {
                textdomain: 'all-in-one-schemaorg-rich-snippets',
            },
            update_all_domains: {
                options: {
                    updateDomains: true
                },
                src: ['*.php', '**/*.php', '!\.git/**/*', '!bin/**/*', '!node_modules/**/*', '!tests/**/*']
            }
        },

        wp_readme_to_markdown: {
            your_target: {
                files: {
                    'README.md': 'readme.txt'
                }
            },
        },

        makepot: {
            target: {
                options: {
                    domainPath: '/languages',
                    exclude: ['\.git/*', 'bin/*', 'node_modules/*', 'tests/*'],
                    mainFile: 'index.php',
                    potFilename: 'all-in-one-schemaorg-rich-snippets.pot',
                    potHeaders: {
                        poedit: true,
                        'x-poedit-keywordslist': true
                    },
                    type: 'wp-plugin',
                    updateTimestamp: true
                }
            }
        },

        zip: {
            'release': {
                src: ['**/*', '!node_modules/**', '!tests/**', '!bin/**', '!**/*.zip'], // Exclude certain folders and existing zip files
                dest: 'all-in-one-schemaorg-rich-snippets.zip'
            }
        },
    });

    grunt.loadNpmTasks('grunt-wp-i18n');
    grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
    grunt.loadNpmTasks('grunt-zip'); // Use grunt-zip instead

    grunt.registerTask('i18n', ['addtextdomain', 'makepot']);
    grunt.registerTask('readme', ['wp_readme_to_markdown']);
    grunt.registerTask('release', ['zip:release']); // Add release command

    grunt.util.linefeed = '\n';
};
