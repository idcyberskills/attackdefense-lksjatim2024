const isLoggedIn = (req, res, next) => {
    if (req.session && req.session.user?.id) {
        return next();
    } else {
        return res.redirect('/login');
    }
};

module.exports = isLoggedIn;