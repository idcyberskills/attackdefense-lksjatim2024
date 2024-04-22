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

  res.render('index', { successMessage, errorMessage });
});

router.get('/register', function(req, res, next) {
  const successMessage = req.session.successMessage;
  const errorMessage = req.session.errorMessage;

  req.session.successMessage = null;
  req.session.errorMessage = null;

  res.render('login', { successMessage, errorMessage });
});

router.post('/login', function(req, res, next) {
  
  res.redirect('/index');
});

router.post('/register', async function(req, res, next) {
    const { username, password, email } = req.body;

    let user = { username, password, email };
    user.password = await bcrypt.hash(password, 10);

    User.create(user)
      .then((data) => {
        req.session.successMessage = 'Registration successful! Please login.';
      })
      .catch((err) => {
        console.log(err);
      });

    res.redirect('/login');
});

module.exports = router;
