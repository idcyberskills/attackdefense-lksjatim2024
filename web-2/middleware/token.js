var nJwt = require('../lib/njwt');

const authenticateJWT = (req, res, next) => {
    const token = req.headers.authorization || req.body.token;
    const signingKey = req.app.signingKey;

    if (!token) {
        return res.status(401).json({ error: 'Unauthorized: No token provided' });
    }

    nJwt.verify(token, signingKey, function(err,verifiedJwt){
        if (err) {
            return res.status(401).json({ error: `Unauthorized: Invalid token. Error: ${err}` });
        }else{
            if (verifiedJwt.body.scope === "admin") {
                next();
            }
            else {
                return res.status(401).json({ error: `Unauthorized: you don't have admin privilege.` });
            }
        }
      });
};

module.exports = authenticateJWT;