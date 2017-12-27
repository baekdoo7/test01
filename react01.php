<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Hello React</title>
    <script src="https://fb.me/react-0.13.3.js"></script>
    <script src="https://fb.me/JSXTransformer-0.13.3.js"></script>
  </head>
  <body>
    <div id="example"></div>
    
      <script type="text/jsx">

var HelloWorld = React.createClass({
  render: function() {
    return (
      <p>
        Hello {this.props.name01}!
      </p>
    );
  }
});

  React.render(
    <HelloWorld name01="World" />,
    document.getElementById('example')
  );

    </script>
  </body>
</html>