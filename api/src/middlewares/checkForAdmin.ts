import { NextFunction, Request, Response } from "express";
import { isAdmin } from "../utils/helpers";

export async function checkForAdmin(req: Request,
    res: Response, next: NextFunction): Promise<void> {
    if (await isAdmin(res.locals.jwtPayload.userId)) {
        next();
    } else {
        res.status(401).send({ message: "Diese Aktion ist nicht erlaubt!" });
    }
}
