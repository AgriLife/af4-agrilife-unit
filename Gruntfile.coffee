module.exports = (grunt) ->
  @initConfig
    pkg: @file.readJSON('package.json')
    watch:
      files: [
        'css/src/*.scss'
      ]
      tasks: ['sasslint', 'sass:dev']
    postcss:
      pkg:
        options:
          processors: [
            require('autoprefixer')({browsers: ['last 2 versions','ie > 9']})
          ]
          failOnError: true
        files:
          'css/admin.css':           'css/admin.css'
          'css/agrilife.css':        'css/agrilife.css'
          'css/home.css':            'css/home.css'
          'css/af4-style-guide.css': 'css/af4-style-guide.css'
      dev:
        options:
          map: true
          processors: [
            require('autoprefixer')({browsers: ['last 2 versions','ie > 9']})
          ]
          failOnError: true
        files:
          'css/admin.css':           'css/admin.css'
          'css/agrilife.css':        'css/agrilife.css'
          'css/home.css':            'css/home.css'
          'css/af4-style-guide.css': 'css/af4-style-guide.css'
    sass:
      pkg:
        options:
          loadPath: 'node_modules/foundation-sites/scss'
          sourcemap: 'none'
          style: 'compressed'
          precision: 2
        files:
          'css/admin.css':           'css/src/admin.scss'
          'css/agrilife.css':        'css/src/agrilife.scss'
          'css/home.css':            'css/src/home.scss'
          'css/af4-style-guide.css': 'css/src/af4-style-guide.scss'
      dev:
        options:
          loadPath: 'node_modules/foundation-sites/scss'
          style: 'expanded'
          precision: 2
          trace: true
        files:
          'css/admin.css':           'css/src/admin.scss'
          'css/agrilife.css':        'css/src/agrilife.scss'
          'css/home.css':            'css/src/home.scss'
          'css/af4-style-guide.css': 'css/src/af4-style-guide.scss'
    sasslint:
      options:
        configFile: '.sass-lint.yaml'
      target: ['scss/**/*.s+(a|c)ss']
    compress:
      main:
        options:
          archive: 'agriflex4.zip'
        files: [
          {src: ['style.css']},
          {src: ['rtl.css']},
          {src: ['css/*.css']},
          {src: ['js/*.js']},
          {src: ['images/**']},
          {src: ['src/*.php']},
          {src: ['functions.php']},
          {src: ['search.php']},
          {src: ['README.md']},
          {src: ['screenshot.png']},
          {src: ['vendor/**']}
        ]

  @loadNpmTasks 'grunt-contrib-sass'
  @loadNpmTasks 'grunt-contrib-watch'
  @loadNpmTasks 'grunt-contrib-compress'
  @loadNpmTasks 'grunt-sass-lint'
  @loadNpmTasks 'grunt-postcss'

  @registerTask 'default', ['sass:pkg', 'postcss:pkg']
  @registerTask 'develop', ['sasslint', 'sass:dev', 'postcss:dev']
  @registerTask 'phpscan', 'Compare results of vip-scanner with known issues', ->
    done = @async()

    # Ensure strings use the same HTML characters
    unescape_html = (str) ->
      str.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace /&gt;/g, '>'

    # Known issues
    known_issues = grunt.file.readJSON('known-issues.json')
    known_issues_string = JSON.stringify(known_issues)
    known_issues_string = unescape_html(known_issues_string)

    # Current issues
    current_issues = grunt.file.read('vipscan.json')
    start = current_issues.indexOf('[{')
    end = current_issues.lastIndexOf('}]')
    current_issues_string = current_issues.slice(start, end) + '}]'
    current_issues_string = unescape_html(current_issues_string)
    current_issues_json = JSON.parse(current_issues_string)

    # New issues
    new_issues = []
    i = 0
    while i < current_issues_json.length
      issue = current_issues_json[i]
      issue_string = JSON.stringify(issue)
      if known_issues_string.indexOf(issue_string) < 0
        new_issues.push(issue)
      i++

    # Display issues information
    grunt.log.writeln('--- VIP Scanner Results ---')
    grunt.log.writeln(known_issues.length + ' known issues.')
    grunt.log.writeln(current_issues_json.length + ' current issues.')
    grunt.log.writeln(new_issues.length + ' new issues:')
    grunt.log.writeln '------------------'
    i = 0
    while i < new_issues.length
      obj = new_issues[i]

      for key,value of obj
        if value != ''
          if Array.isArray(value)
            value = value.join(' ')
            grunt.log.writeln(key + ': ' + value)
          else if typeof value == 'object'
            grunt.log.writeln(key + ':')
            for key2,value2 of value
              grunt.log.writeln('>> Line ' + key2 + ': ' + value2)
          else
            grunt.log.writeln(key + ': ' + value)

      grunt.log.writeln '------------------'
      i++

    grunt.log.writeln 'All new issues as JSON: '
    grunt.log.writeln JSON.stringify(new_issues)

    return

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
