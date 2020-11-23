import * as emailTemplates from "email-templates";
import { Request } from "express";
import * as nodeMailer from "nodemailer";
import * as path from "path";

export function sendMail(req: Request, to: string, data: {summary: string;
    title: string; subtitle: string; secondTitle: string; content: string; subject: string;
    cardTitle: string; cardSubtitle: string; btnText: string; btnUrl: string;}): Promise<void> {
    const locals: any = data;
    locals.sentTo = `Diese Mail wurde an ${to} gesendet.`;
    locals.info = "Wenn Sie diese Mail versehentlich erhalten haben, bitten wir Sie, sie zu löschen und nicht weiterzuleiten, da diese Mail vertrauliche Informationen enthalten kann.";
    return new Promise<any>((resolve, reject) => {
        const transporter = nodeMailer.createTransport({
            auth: {
                pass: req.app.locals.config.MAIL_SERVER_PASSWORD,
                user: req.app.locals.config.MAIL_SERVER_USER,
            },
            host: req.app.locals.config.MAIL_SERVER_HOST,
            port: req.app.locals.config.MAIL_SERVER_PORT,
        });
        // eslint-disable-next-line new-cap
        const email = new emailTemplates({
            message: { from: req.app.locals.config.MAIL_SENDER },
            preview: false,
            send: true,
            transport: transporter,
            views: {
                options: {
                    extension: "ejs",
                },
                root: path.resolve(__dirname, "../../assets"),
            },
        });
        email
            .send({
                locals,
                message: { to },
                template: "mail",
            })
            .then((info) => {
                resolve(info);
            })
            .catch((err) => {
                reject(err);
            });
    });
}
