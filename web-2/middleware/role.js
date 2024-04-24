const hasAdminPermission = (req, res, next) => {
    if (req.session && req.session.user?.isAdmin === true) {
        return next()
    } else {
        req.session.errorMessage = 'You don\'t have admin permission!';
        return res.redirect('/login');
    }
};

module.exports = hasAdminPermission;