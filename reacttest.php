<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Hello React</title>
    <script src="https://fb.me/react-0.13.3.js"></script>
    <script src="https://fb.me/JSXTransformer-0.13.3.js"></script>
  </head>
  <body>
    <div id="content"></div>
    <script type="text/jsx">

      // ** Example Template **
var Comment = React.createClass({
  render: function() {
    return (
      <div className="comment">
        <h2 className="commentAuthor"> {this.props.author} </h2>
        {this.props.children}
      </div>
    );
  }
});
var CommentForm = React.createClass({
  render: function() {
    return (
      <div className="commentForm"> 안녕! 난 댓글 폼이야. </div>
    );
  }
});
var CommentList = React.createClass({
  render: function() {
    var commentNodes = this.props.data.map(function (comment) {
      return (
        <Comment author={comment.author}>
          {comment.text}
        </Comment>
      );
    });
    return (
      <div className="commentList">
        {commentNodes}
      </div>
    );
  }
});
var CommentBox = React.createClass({
  render: function() {
    return (
      <div className="commentBox">
        <h1>댓글</h1>
        <CommentList data={this.props.data} />
        <CommentForm />
      </div>
    );
  }
});
var data = [
  {author: "Pete Hunt", text: "댓글입니다"},
  {author: "Jordan Walke", text: "*또 다른* 댓글입니다"}
];
React.render( <CommentBox data={data} />, document.getElementById('content') );
    </script>
  </body>
</html>