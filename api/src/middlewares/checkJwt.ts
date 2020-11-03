import { NextFunction, Request, Response } from "express";
import * as jwt from "jsonwebtoken";
import { getRepository } from "typeorm";
import { User } from "../entity/User";

export async function checkJwt(req: Request, res: Response, next: NextFunction): Promise<void> {
    // Get the jwt token from the head
    let token = req.headers.authorization as string;
    if (!token) {
        token = req.query.authorization;
    }
    if (!token || typeof token !== "string") {
        res.status(401).send({ message: "Sitzung abgelaufen!" });
        return;
    }
    token = token.replace("Bearer ", "");
    let jwtPayload;

    // Try to validate the token and get data
    try {
        jwtPayload = (jwt.verify(token, req.app.locals.config.JWT_SECRET) as any);
        res.locals.jwtPayload = jwtPayload;
        try {
            res.locals.jwtPayload.user = await getRepository(User)
                .findOneOrFail(res.locals.jwtPayload.userId);
        } catch {
            res.status(401).send({ message: "Der Benutzer wurde nicht gefunden!", logout: true });
        }
    } catch (error) {
    // If token is not valid, respond with 401 (unauthorized)
        res.status(401).send({ message: "Sitzung abgelaufen!", logout: true });
        return;
    }

    // The token is valid for 1 hour
    // We want to send a new token on every request
    const { userId, name, isAdmin } = jwtPayload;
    const newToken = jwt.sign({ userId, name, isAdmin }, req.app.locals.config.JWT_SECRET, {
        expiresIn: "1h",
    });
    res.setHeader("Authorization", newToken);

    // Call the next middleware or controller
    next();
}
