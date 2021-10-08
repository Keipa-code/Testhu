import React, {useState} from 'react';
import {Col, Form, FormGroup, Row} from "react-bootstrap";

interface answer {
    id: number;
    correct: boolean;
    text: string;
}

const AnswerChooseItem = (answer) => {
    const [answerText, setAnswerText] = useState<answer.text>('');
    const [correctAnswers, setCorrectAnswers] = useState<[]>([]);

    const changeAnswerText = (e: React.ChangeEvent<HTMLInputElement>) => {
        setAnswerText(e.target.value)
    }
    let checked = false

    return (
        <div>
            <Form>
                <Row className="align-items-center">
                    <Col xs="auto">
                        <Form.Control
                            placeholder="Текст ответа"
                            onChange={changeAnswerText}
                        />
                    </Col>

                    <Col xs="auto">
                        <Form.Check
                            type="checkbox"
                            checked={checked}
                            className="mb-2"
                            label="Правильный ответ"
                        />
                    </Col>

                </Row>
            </Form>

        </div>
    );
};

export default AnswerChooseItem;
