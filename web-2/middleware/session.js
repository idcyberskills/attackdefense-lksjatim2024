const isLoggedIn = (req, res, next) => {
    if (req.session && req.session.user?.id) {
        return next();
    } else {
        req.session.errorMessage = 'Please login first!';
        return res.redirect('/login');
    }
};

module.exports = isLoggedIn;