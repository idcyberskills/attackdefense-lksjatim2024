var express = require('express');
const bcrypt = require('bcryptjs');

const db = require("../models");
const User = db.users;

var router = express.Router();

router.get('/login', function(req, res, next) {
  const successMessage = req.session.successMessage;
  const errorMessage = req.session.errorMessage;

  req.session.successMessage = null;
  req.session.errorMessage = null;

  res.render('login', { successMessage, errorMessage });
});

router.get('/register', function(req, res, next) {
  const successMessage = req.session.successMessage || '';
  const errorMessage = req.session.errorMessage || '';

  req.session.successMessage = null;
  req.session.errorMessage = null;

  res.render('register', { successMessage, errorMessage });
});

router.post('/login', async function(req, res, next) {
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

  return res.redirect('/home');
});

router.post('/register', async function(req, res, next) {
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
