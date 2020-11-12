import { Request, Response } from "express";
import { getRepository } from "typeorm";
import { User } from "../entity/User";

class UserController {
    public static listUsers = async (req: Request, res: Response): Promise<void> => {
        const userRepository = getRepository(User);
        const users = await userRepository.find();
        res.send(users);
    }

    public static newUser = async (req: Request, res: Response): Promise<void> => {
        const {
            nickname, password, password2, realname, grade,
        } = req.body;
        if (!(nickname && realname && password && password2 && grade)) {
            res.status(400).send({ message: "Nicht alle Felder ausgefüllt!" });
            return;
        }
        if (password != password2) {
            res.status(400).send({ message: "Passwörter stimmen nicht überein!" });
            return;
        }
        const grades: string[] = ["Lehrerin / Lehrer", "Studienseminar 18/20", "Studienseminar 19/21", "Studienseminar 20/22", "5a", "5b", "5c", "5d", "5e", "5f", "6a", "6b", "6c", "6d", "6e", "6f", "7a", "7b", "7c", "7d", "7e", "7f", "8a", "8b", "8c", "8d", "8e", "8f", "9a", "9b", "9c", "9d", "9e", "9f", "10a", "10b", "10c", "10d", "10e", "10f", "Q11", "Q12"];
        if (!grades.includes(grade)) {
            res.status(400).send({ message: "Die Klasse ist ungültig!" });
            return;
        }

        let user = new User();
        user.nickname = nickname;
        user.realname = realname;
        user.password = password;
        user.grade = grade;
        user.isAdmin = false;

        user.hashPassword();

        const userRepository = getRepository(User);
        try {
            user = await userRepository.save(user);
        } catch (e) {
            res.status(409).send({ message: "Der Nickname ist schon vorhanden!", errorField: "username" });
            return;
        }
        res.status(200).send({ success: true });
    }

    public static deleteUser = async (req: Request, res: Response): Promise<void> => {
        const { id } = req.params;

        const userRepository = getRepository(User);
        try {
            await userRepository.delete(id);
        } catch (e) {
            res.status(500).send({ message: "Konnte den Benutzer nicht löschen!" });
            return;
        }
        res.status(200).send({ success: true });
    }

    public static changeAdminStatus = async (req: Request, res: Response): Promise<void> => {
        const { id } = req.params;
        const { admin } = req.body;

        const userRepository = getRepository(User);
        try {
            const user = await userRepository.findOne(id);
            user.isAdmin = admin;
            await userRepository.save(user);
        } catch (e) {
            res.status(500).send({ message: "Konnte den Adminstatus nicht ändern!" });
            return;
        }
        res.status(200).send({ success: true });
    }
}

export default UserController;
