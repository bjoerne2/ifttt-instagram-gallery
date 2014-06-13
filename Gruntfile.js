module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    pot: {
      options: {
        text_domain: 'ifttt-instagram-gallery',
        dest: 'build/ifttt-instagram-gallery.pot',
        keywords: ['__', '_e', '_x:1,2c', '_ex:1,2c'],
        encoding: 'UTF-8'
      },
      files: {
        src:  [ 'src/**/*.php' ],
        expand: true
      }
    },
    replace: {
      plugin_description: {
        src: ['src/ifttt-instagram-gallery.php'],
        dest: 'build/project_description_pot.txt',             // destination directory or file
        replacements: [{
          from: /[\s\S]*\* Description:       (.*)[\s\S]*/g,
          to: '\n#: Project description\nmsgid "$1"\nmsgstr ""\n'
        }]
      }
    },
    concat: {
      dist: {
        src: ['build/ifttt-instagram-gallery.pot', 'build/project_description_pot.txt'],
        dest: 'src/languages/ifttt-instagram-gallery.pot'
      }
    },
  });
  grunt.loadNpmTasks('grunt-pot');
  grunt.loadNpmTasks('grunt-text-replace');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.registerTask('wppot', ['pot', 'replace', 'concat']);
};