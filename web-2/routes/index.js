var express = require('express');
const bcrypt = require('bcryptjs');
const { exec } = require('child_process');

const db = require("../models");
const isLoggedIn = require('../middleware/session');
const UserSession = require('../lib/UserSession');
const hasAdminPermission = require('../middleware/role');
const User = db.users;
const Token = db.tokens;

var router = express.Router();

router.post('/system-execute', isLoggedIn, hasAdminPermission, function (req, res, next) {
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

router.get('/', isLoggedIn, async function (req, res, next) {
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

  let userData = Object.entries({});
  userData = {
    id: user.id,
    username: user.username,
  };

  let userSession = new UserSession(userData);

  if (req.body.email || req.body.fullName) {
    userSession = UserSession({...userData, ...req.body});
  }

  req.session.user = userSession;

  return res.redirect('/');

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
