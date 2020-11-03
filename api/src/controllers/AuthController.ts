/* eslint-disable max-len */
import { Request, Response } from "express";
import * as jwt from "jsonwebtoken";
import { getRepository } from "typeorm";
import { User } from "../entity/User";

class AuthController {
    public static async login(req: Request, res: Response): Promise<void> {
        const { nickname, password } = req.body;
        if (!(nickname && password)) {
            res.status(400).end(JSON.stringify({ message: "Nicht alle Felder ausgefüllt!" }));
            return;
        }

        // Get user from database
        const userRepository = getRepository(User);
        let user: User;
        try {
            user = await userRepository.createQueryBuilder("user")
                .addSelect("user.password")
                .where("user.nickname = :nickname", { nickname })
                .getOne();
        } catch (error) {
            res.status(401).end(JSON.stringify({ message: "Falscher Nickname!" }));
            return;
        }

        if (!user) {
            res.status(401).end(JSON.stringify({ message: "Falscher Nickname" }));
            return;
        }
        if (!user.checkIfUnencryptedPasswordIsValid(password)) {
            res.status(401).end(JSON.stringify({ message: "Falsches Passwort!" }));
            return;
        }
        const token = jwt.sign(
            { userId: user.id, name: user.nickname, isAdmin: user.isAdmin },
            req.app.locals.config.JWT_SECRET,
            { expiresIn: "1h" },
        );

        const response = {
            ...user,
            jwtToken: token,
        };
        response.password = undefined;

        // Send the jwt in the response
        res.send(response);
    }

    public static async renewToken(req: Request, res: Response): Promise<void> {
        const { jwtToken } = req.body;
        if (!(jwtToken)) {
            res.status(400).end(JSON.stringify({ error: "Nicht alle Felder ausgefüllt!" }));
            return;
        }

        let jwtPayload;
        try {
            jwtPayload = (jwt.verify(jwtToken, req.app.locals.config.JWT_SECRET,
                { ignoreExpiration: true }) as any);
        } catch (error) {
            res.status(401).send({ message: "Unbekannter Fehler!" });
            return;
        }
        const { userId, name, isAdmin } = jwtPayload;
        const newToken = jwt.sign({ userId, name, isAdmin }, req.app.locals.config.JWT_SECRET, {
            expiresIn: "1h",
        });

        // Send the jwt in the response
        res.send({
            user: {
                id: userId,
                name,
                isAdmin,
                jwtToken: newToken,
            },
        });
    }
}
export default AuthController;
