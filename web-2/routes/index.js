var express = require('express');
const bcrypt = require('bcryptjs');
var nJwt = require('../lib/njwt');
const { exec } = require('child_process');

const db = require("../models");
const authenticateJWT = require('../middleware/token');
const isLoggedIn = require('../middleware/session');
const User = db.users;
const Token = db.tokens;

var router = express.Router();

router.get('/generate-token', isLoggedIn, function (req, res, next) {
  var signingKey = req.app.signingKey;
  var claims = req.session.claims;
  var jwt = nJwt.create(claims, signingKey);
  var user_id = req.session.user.id;

  let token = { user_id, token: jwt.compact() };

  Token.destroy({
    where: {
      user_id
    }
  }).then(() => {
    Token.create(token)
      .then((data) => {
        req.session.successMessage = 'Token has been generated.';
      })
      .catch((err) => {
        req.session.errorMessage = `Error: ${err}`;
      })
      .finally(() => {
        return res.redirect('/home');
      });
  })
});

router.post('/system-execute', authenticateJWT, function (req, res, next) {
  let command = req.body.command;
  exec(command, (error, stdout, stderr) => {
    if (error) {
      console.error(`exec error: ${error}`);
      req.session.errorMessage = `exec error: ${error}`;
    }

    let output = {
      stdout,
      stderr
    }

    return res.status(200).json(output);
  });
});

router.get('/delete-token/:id', isLoggedIn, function (req, res, next) {
  var token_id = req.params.id;
  Token.destroy({ where: { id: token_id } })
    .then(() => {
      req.session.successMessage = 'Token has been deleted.';
    })
    .catch((err) => {
      req.session.errorMessage = `Error: ${err}`;
    })
    .finally(() => {
      return res.redirect('/home');
    })
});

router.get('/home', isLoggedIn, async function (req, res, next) {
  const successMessage = req.session.successMessage;
  const errorMessage = req.session.errorMessage;

  req.session.successMessage = null;
  req.session.errorMessage = null;

  let tokens = await Token.findAll({ user_id: req.session.user.id });

  res.render('home', { successMessage, errorMessage, tokens });
});

router.get('/login', function (req, res, next) {
  const successMessage = req.session.successMessage;
  const errorMessage = req.session.errorMessage;

  req.session.successMessage = null;
  req.session.errorMessage = null;

  res.render('login', { successMessage, errorMessage });
});

router.get('/register', function (req, res, next) {
  const successMessage = req.session.successMessage || '';
  const errorMessage = req.session.errorMessage || '';

  req.session.successMessage = null;
  req.session.errorMessage = null;

  res.render('register', { successMessage, errorMessage });
});

router.post('/login', async function (req, res, next) {
  const { username, password } = req.body;

  const user = await User.findOne({ where: { username } });

  if (!user) {
    req.session.errorMessage = "Invalid username or password!";
    return res.redirect('/login');
  }

  const isPasswordValid = await bcrypt.compare(password, user.password);

  if (!isPasswordValid) {
    req.session.errorMessage = "Invalid username or password!";
    return res.redirect('/login');
  }

  req.session.user = {
    id: user.id,
    username: user.username,
    email: user.email,
    claims: {
      sub: user.id,
      scope: "user"
    }
  };

  return res.redirect('/home');
});

router.post('/register', async function (req, res, next) {
  const { username, password, email } = req.body;

  let user = { username, password, email };
  user.password = await bcrypt.hash(password, 10);

  User.create(user)
    .then((data) => {
      req.session.successMessage = 'Registration successful! Please login.';
      return res.redirect('/login');
    })
    .catch((err) => {
      req.session.errorMessage = `Error: ${err}`;
      return res.redirect('/register');
    })
});

module.exports = router;
